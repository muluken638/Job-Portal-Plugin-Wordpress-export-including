<?php
/**
 * Handles Excel export for applicants
 */

if (!defined('ABSPATH')) {
    exit;
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MJ_Job_Export {
    public static function init() {
        add_action('admin_init', [__CLASS__, 'cee_export_excel']);
    }

    public static function cee_export_excel() {
        if (!current_user_can('access_excel_export') && !current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        $date_from = $_GET['date_from'] ?? '';
        $date_to = $_GET['date_to'] ?? '';
        $gender = $_GET['gender'] ?? '';
        $educational_level = $_GET['educational_level'] ?? '';
        $marital_status = $_GET['marital_status'] ?? '';
        $job_category = $_GET['job_category'] ?? '';
        $job_title = $_GET['job_title'] ?? '';
        $job_type = $_GET['job_type'] ?? '';
        $job_location = $_GET['job_location'] ?? '';

        // Step 1: Get Job IDs if job filters are selected
        $job_ids = [];
        if ($job_category || $job_type || $job_location || $job_title) {
            $job_args = ['post_type' => 'jobpost', 'numberposts' => -1, 'fields' => 'ids'];
            $tax_query = ['relation' => 'AND'];

            if ($job_category) {
                $tax_query[] = ['taxonomy' => 'jobpost_category', 'field' => 'name', 'terms' => [$job_category]];
            }
            if ($job_type) {
                $tax_query[] = ['taxonomy' => 'jobpost_job_type', 'field' => 'name', 'terms' => [$job_type]];
            }
            if ($job_location) {
                $tax_query[] = ['taxonomy' => 'jobpost_location', 'field' => 'name', 'terms' => [$job_location]];
            }
            if (count($tax_query) > 1) {
                $job_args['tax_query'] = $tax_query;
            }

            if ($job_title) {
                $title_post = get_page_by_title($job_title, OBJECT, 'jobpost');
                if ($title_post) {
                    $job_args['post__in'] = [$title_post->ID];
                }
            }

            $job_ids = get_posts($job_args);
        }

        // Step 2: Get applicants
        $app_args = ['post_type' => 'jobpost_applicants', 'numberposts' => -1, 'meta_query' => ['relation' => 'AND']];
        if ($gender) {
            $app_args['meta_query'][] = ['key' => 'jobapp_gender', 'value' => $gender, 'compare' => '='];
        }
        if ($educational_level) {
            $app_args['meta_query'][] = ['key' => 'jobapp_educational_level', 'value' => $educational_level, 'compare' => '='];
        }
        if ($marital_status) {
            $app_args['meta_query'][] = ['key' => 'jobapp_marital_status', 'value' => $marital_status, 'compare' => '='];
        }
        if (!empty($job_ids)) {
            $app_args['meta_query'][] = ['key' => 'jobapp_job_id', 'value' => $job_ids, 'compare' => 'IN'];
        }

        if ($date_from || $date_to) {
            $date_query = ['inclusive' => true, 'column' => 'post_date'];
            if ($date_from) {
                $date_query['after'] = $date_from;
            }
            if ($date_to) {
                $date_query['before'] = $date_to;
            }
            $app_args['date_query'] = [$date_query];
        }

        $apps = get_posts($app_args);

        // Excel setup
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $main_headers = ['S/N', 'Full Name', 'Gender', 'Age', 'Marital Status', 'Birth Place (Region)', 'Birth Place (City/Town)', 'Current Address', 'Level of Education', 'Field of Study', 'Year of Education', 'University/ College', 'CGPA'];
        $col = 'A';
        foreach ($main_headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->mergeCells($col . '1:' . $col . '2');
            $sheet->getStyle($col . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        // Experience header
        $sheet->mergeCells('N1:R1');
        $sheet->setCellValue('N1', 'Three Years of Experience');
        $sheet->getStyle('N1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('N1')->getFont()->setBold(true);
        $exp_headers = ['Organization', 'Position', 'From–To (E.C)', 'Year of Exp.', 'Total Year of Exp.'];
        $sheet->fromArray($exp_headers, NULL, 'N2');
        $sheet->getStyle('N2:R2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('N2:R2')->getFont()->setBold(true);

        $other_headers = ['Current Gross Salary', 'Expected Gross Salary', 'Telephone'];
        $col = 'S';
        foreach ($other_headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->mergeCells($col . '1:' . $col . '2');
            $sheet->getStyle($col . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        // Fill data
        $row_num = 3;
        foreach ($apps as $i => $app) {
            $meta = get_post_meta($app->ID);

            $experiences = [
                [
                    'company' => $meta['jobapp_current_company'][0] ?? '',
                    'position' => $meta['jobapp_current_position'][0] ?? '',
                    'from' => $meta['jobapp_experience_in_current_company_from'][0] ?? '',
                    'to' => $meta['jobapp_experience_in_current_company_to'][0] ?? '',
                    'years' => mj_calculate_years($meta['jobapp_experience_in_current_company_from'][0] ?? '', $meta['jobapp_experience_in_current_company_to'][0] ?? ''),
                    'total' => $meta['jobapp_total_year_of_experience'][0] ?? ''
                ],
                [
                    'company' => $meta['jobapp_previous_company_1'][0] ?? '',
                    'position' => $meta['jobapp_previous_position_in_company_1'][0] ?? '',
                    'from' => $meta['jobapp_experience_in_previous_company_1_from'][0] ?? '',
                    'to' => $meta['jobapp_experience_in_previous_company_1_to'][0] ?? '',
                    'years' => mj_calculate_years($meta['jobapp_experience_in_previous_company_1_from'][0] ?? '', $meta['jobapp_experience_in_previous_company_1_to'][0] ?? ''),
                    'total' => ''
                ],
                [
                    'company' => $meta['jobapp_prev_company2'][0] ?? '',
                    'position' => $meta['jobapp_prev_position2'][0] ?? '',
                    'from' => $meta['jobapp_prev_from2'][0] ?? '',
                    'to' => $meta['jobapp_prev_to2'][0] ?? '',
                    'years' => mj_calculate_years($meta['jobapp_prev_from2'][0] ?? '', $meta['jobapp_prev_to2'][0] ?? ''),
                    'total' => ''
                ]
            ];

            $experiences = array_filter($experiences, function ($exp) {
                return $exp['company'] || $exp['position'] || $exp['from'] || $exp['to'] || $exp['years'];
            });
            $exp_count = count($experiences);
            $start_row = $row_num;

            $main_cols = range('A', 'M');
            foreach ($main_cols as $mc) {
                $value = '';
                switch ($mc) {
                    case 'A': $value = $i + 1; break;
                    case 'B': $value = $meta['jobapp_name'][0] ?? ''; break;
                    case 'C': $value = $meta['jobapp_gender'][0] ?? ''; break;
                    case 'D': $value = $meta['jobapp_age'][0] ?? ''; break;
                    case 'E': $value = $meta['jobapp_marital_status'][0] ?? ''; break;
                    case 'F': $value = $meta['jobapp_birth_place_state'][0] ?? ''; break;
                    case 'G': $value = $meta['jobapp_birth_place_city'][0] ?? ''; break;
                    case 'H': $value = $meta['jobapp_current_address'][0] ?? ''; break;
                    case 'I': $value = $meta['jobapp_educational_level'][0] ?? ''; break;
                    case 'J': $value = $meta['jobapp_field_of_study'][0] ?? ''; break;
                    case 'K': $value = $meta['jobapp_year_of_graduation'][0] ?? ''; break;
                    case 'L': $value = $meta['jobapp_university_college'][0] ?? ''; break;
                    case 'M': $value = $meta['jobapp_cgpa'][0] ?? ''; break;
                }
                $sheet->setCellValue($mc . $row_num, $value);
                if ($exp_count > 1) {
                    $sheet->mergeCells($mc . $row_num . ':' . $mc . ($row_num + $exp_count - 1));
                }
                $sheet->getStyle($mc . $row_num)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }

            foreach ($experiences as $exp) {
                $sheet->setCellValue('N' . $row_num, $exp['company']);
                $sheet->setCellValue('O' . $row_num, $exp['position']);
                $sheet->setCellValue('P' . $row_num, $exp['from'] . '–' . $exp['to']);
                $sheet->setCellValue('Q' . $row_num, $exp['years']);
                $sheet->setCellValue('R' . $row_num, $exp['total']);
                $row_num++;
            }

            $salary_cols = ['S', 'T', 'U'];
            foreach ($salary_cols as $sc) {
                $value = '';
                switch ($sc) {
                    case 'S': $value = $meta['jobapp_current_gross_salary'][0] ?? ''; break;
                    case 'T': $value = $meta['jobapp_expected_gross_salary'][0] ?? ''; break;
                    case 'U': $value = $meta['jobapp_phone'][0] ?? ''; break;
                }
                $sheet->setCellValue($sc . $start_row, $value);
                if ($exp_count > 1) {
                    $sheet->mergeCells($sc . $start_row . ':' . $sc . ($row_num - 1));
                }
                $sheet->getStyle($sc . $start_row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }

            for ($r = $start_row; $r < $row_num; $r++) {
                $fillColor = ($r % 2 == 0) ? 'FFEFEFEF' : 'FFFFFFFF';
                $sheet->getStyle('A' . $r . ':U' . $r)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($fillColor);
            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="applicants.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
add_action('init', ['MJ_Job_Export', 'init']);
?>