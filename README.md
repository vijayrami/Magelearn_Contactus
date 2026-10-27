# Magelearn_Contactus

Magento 2 module that stores successful Magento Contact Us submissions in `magelearn_contactus` and exposes them through an Admin UI listing.

## Architecture

- `Plugin/ContactusPlugin.php` hooks into `Magento\Contact\Controller\Index\Post::execute()` and persists only successful submissions.
- `Model/Contactus.php` is the domain model.
- `Model/ResourceModel/Contactus.php` owns database persistence.
- `Model/ResourceModel/Contactus/Collection.php` is the normal collection.
- `Model/ResourceModel/Contactus/Grid/Collection.php` implements `SearchResultInterface` for the UI grid.
- The Admin grid uses Magento's standard `DataProvider` through a virtual type; no custom DataProvider class is required.
- Admin submissions are intentionally read-only. Delete and mass-delete are supported.
- `etc/db_schema.xml` replaces legacy `InstallSchema`/`UpgradeSchema` code.

## Install / upgrade

For a new installation:

```bash
bin/magento module:enable Magelearn_Contactus
bin/magento setup:upgrade
bin/magento cache:clean
```

For production mode:

```bash
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

### Existing installation note

This version uses declarative schema. If replacing the old module that already has `setup_version` and the `magelearn_contactus` table, review the existing database schema before deployment. Do not blindly drop the old table; preserve existing submissions and reconcile the legacy schema/indexes with `db_schema.xml` during the deployment migration.
