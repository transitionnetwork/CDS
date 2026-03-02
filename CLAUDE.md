Development URL https://cds.ddev site. You may use chrome to observe the site.

Please do these in order bearing in mind that we don't run into issues where we run out of context tokens (or degrade quality) as I've eperienced in Github Copilot. We don't need to do them in a single context if running out of context is a potential issue.

The main aim of this project is to convert the theme from a bootstrap to a daisy-ui codebase. You have access to the daisy-ui MCP server.

DO NOT PROCEED unless you have access to daisyui-blueprint MCP

Please go ahead and make changes to files and run local commands without asking for permission.

- Please install tailwind / daisy ui and test
- Please update bootstrap classes in template php files to equivalent daisy ui / bootstrap classes. Please be approximate with text sizing, spacing etc so that we use the native class sizes / spacing rather than specifying pixel amounts within the class name.
- Please ensure that boootstrap JS functions now work with Daisy (including mobile menu). jQuery should be ported to vanilla Javascript
- Please convert scss files to css files. Again, use tailwind / daisy ui classes where possible and round pixel amounts to classes (eg py-2)
- Please install and check that browser-sync is installed and working with vite. URL should be confugured at .env and manifest.json can be discarded
- Please ensure that SVGs are displaying correctly using tailwind and alter the existing SVG function svg() if necessary so that colors are inherited by SVGS. Feel free to amend source SVG sprites to include currentColor if required. Rewrite whatever is needed.
- Please clean up and remove legacy packages that are no longer required
