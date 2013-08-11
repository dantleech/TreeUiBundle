# WIP Tree UI Core Bundle

The Tree UI bundle is a WIP to replace the CMF TreeBrowserBundle.

Feature parity with TreeBrowserBundle:

- Tree browser: YES
- Tree form type: YES
- Context menu: YES (with FancyTreeView)
- Drag and drop: YES (with FancyTreeView)
- Tree state memory: NO, not yet

Some additional important features:

- Metadata system (including annotations)
- Zero configuration for node hierachy (this is done via. metadata)
- Configurable CRUD routes
- No coupling to models or views
  - Provides API for models to implement, e.g. PHPCR-ODM, ORM, Filesystem
  - Provides API for view controllers, e.g. FancyTree, ElFinder.
- Specific models / views packaged seperately
  - Except for Filesystem (zero-dependencies, good for testing)
  - and .. Static HTML view, pretty useless atm, but at least it doesn't have
    any deps :) .. again good for testing.
- Multi-select form type

See also:

- https://github.com/dantleech/TreeUiPhpcrOdmModelBundle
- https://github.com/dantleech/TreeUiFancyTreeViewBundle
