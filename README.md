job-portal/  
├── job-portal.php                  # Main plugin file (plugin header, activation hooks, etc.)  
├── uninstall.php                   # Cleanup on uninstall (optional)  
├── includes/  
│   ├── class-job-portal.php        # Main class for plugin logic (init, enqueue, etc.)  
│   ├── class-job-post-types.php    # Registers custom post types (jobs, applicants)  
│   ├── class-job-taxonomies.php    # Registers taxonomies (categories, types, locations)  
│   ├── class-job-forms.php         # Handles forms for adding jobs, viewing applicants, filters  
│   ├── class-job-export.php        # Export logic (includes the cee_export_excel function or similar)  
│   ├── class-job-address.php       # Handles cascading address logic (regions, zones, weredas, kebeles)  
│   ├── functions.php               # Helper functions (e.g., calculate_years)  
│   └── admin/  
│       ├── admin-menu.php          # Adds admin menu pages (e.g., for viewing applicants, export)  
│       ├── admin-jobs.php          # Admin functions for jobs (add, view)  
│       └── admin-applicants.php    # Admin functions for applicants (view, filter)  
├── public/  
│   ├── class-public.php            # Public-facing logic (shortcodes, templates for job views)  
│   ├── templates/  
│   │   ├── job-add-form.php        # Template for adding jobs  
│   │   ├── job-view.php            # Template for viewing jobs  
│   │   ├── applicants-view.php     # Template for viewing applicants with filters  
│   │   └── address-fields.php      # Reusable template for cascading address fields  
│   └── shortcodes.php              # Shortcodes for embedding forms/views on pages  
├── assets/  
│   ├── css/  
│   │   ├── admin.css               # Admin-specific styles (e.g., for menus, forms)  
│   │   ├── public.css              # Public-facing styles (e.g., job listings, forms)  
│   │   └── export.css              # Optional styles if needed for export previews  
│   ├── js/  
│   │   ├── admin.js                # Admin scripts (e.g., AJAX for filters, export)  
│   │   ├── public.js               # Public scripts (e.g., form validation)  
│   │   └── address-cascade.js      # JS for cascading dropdowns (region -> zone -> wereda -> kebele)  
│   └── images/                     # Plugin icons, placeholders (e.g., for jobs)  
├── languages/  
│   └── job-portal.pot              # Translation template file  
├── vendor/                         # Third-party libraries (e.g., PhpSpreadsheet for Excel export)  
│   └── phpoffice/  
│       └── phpspreadsheet/         # Composer-installed PhpSpreadsheet library  
└── readme.txt                      # Plugin readme (description, installation, FAQs)
