# Magelearn_Contactus

A Magento 2 module that captures successful Magento Contact Us form submissions and stores them in a dedicated database table. The submissions are displayed in a read-only Admin UI grid with filtering, sorting, pagination, column management, and delete/mass-delete functionality.

## Features

- Captures Magento Contact Us form submissions.
- Stores submitted contact information in `magelearn_contactus`.
- Persists the current store ID with each submission.
- Provides an Admin UI grid for viewing submissions.
- Supports:
  - Sorting
  - Filtering
  - Pagination
  - Column visibility
  - Column resizing
  - Bookmarks
  - Mass delete
  - Individual delete
- Uses Magento's standard UI DataProvider.
- Uses Magento's `SearchResult` virtual type for the Admin grid collection.
- Uses declarative database schema with `db_schema.xml`.
- Does not override Magento's core Contact Us controller.
- Uses a plugin to integrate with the Magento Contact Us submission flow.
- Keeps submitted contact records read-only from the Admin grid.

---

## Module Information

| Property | Value |
|---|---|
| Module | `Magelearn_Contactus` |
| Vendor | `Magelearn` |
| Package | `magelearn/module-contactus` |
| Database Table | `magelearn_contactus` |
| Admin Route | `mladmincontactus` |
| Admin Menu | Contact Us |
| Magento Version | Magento 2.x |
| PHP | PHP 8.x recommended |

---

# Architecture

The module follows Magento 2's standard separation of concerns.

```text
Magento Contact Us Form
        │
        ▼
Magento\Contact\Controller\Index\Post
        │
        ▼
ContactusPlugin
        │
        ▼
Magelearn\Contactus\Model\Contactus
        │
        ▼
Magelearn\Contactus\Model\ResourceModel\Contactus
        │
        ▼
magelearn_contactus
        │
        ▼
Admin UI Listing
        │
        ▼
Magento UI DataProvider
        │
        ▼
Magento SearchResult
        │
        ▼
Admin Grid
```

---

## Model Layer

### Model

```text
Model/Contactus.php
```

Responsible for representing a Contact Us submission.

```php
Magelearn\Contactus\Model\Contactus
```

The model extends Magento's `AbstractModel` and is associated with the Contact Us ResourceModel.

---

### Resource Model

```text
Model/ResourceModel/Contactus.php
```

Responsible for database persistence.

```php
Magelearn\Contactus\Model\ResourceModel\Contactus
```

It maps the model to:

```text
magelearn_contactus
```

with:

```text
id
```

as the primary key.

---

### Collection

```text
Model/ResourceModel/Contactus/Collection.php
```

This is the normal Magento model collection used for application-level operations.

It extends:

```php
Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
```

and initializes:

```text
Magelearn\Contactus\Model\Contactus
Magelearn\Contactus\Model\ResourceModel\Contactus
```

The normal collection is intentionally kept separate from the Admin UI grid collection.

---

# Admin Grid Architecture

The Admin grid uses Magento's standard UI component architecture.

```text
UI Component Listing
        │
        ▼
DataProvider
        │
        ▼
CollectionFactory
        │
        ▼
SearchResult virtual type
        │
        ▼
magelearn_contactus
```

The grid should **not** use the normal domain-model collection directly as a `SearchResultInterface`.

Instead, the Admin grid collection is configured as a virtual type of:

```php
Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
```

This allows Magento to return the UI component's expected data documents and prevents errors such as:

```text
Warning: foreach() argument must be of type array|object, null given
```

from:

```text
Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
```

---

## Grid Collection

The Admin grid collection is configured as a virtual type in:

```text
etc/adminhtml/di.xml
```

Conceptually:

```xml
<virtualType
    name="Magelearn\Contactus\Model\ResourceModel\Contactus\Grid\Collection"
    type="Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult">

    <arguments>
        <argument name="mainTable" xsi:type="string">
            magelearn_contactus
        </argument>

        <argument name="resourceModel" xsi:type="string">
            Magelearn\Contactus\Model\ResourceModel\Contactus
        </argument>
    </arguments>

</virtualType>
```

The collection is then registered with:

```php
Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory
```

using the UI datasource name:

```text
mladmincontactus_contactus_listing_data_source
```

### Important

A separate PHP class such as:

```text
Model/ResourceModel/Contactus/Grid/Collection.php
```

is **not required**.

The recommended implementation uses Magento's built-in `SearchResult` class through a virtual type.

---

# UI DataProvider

The grid uses Magento's standard:

```php
Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
```

No custom DataProvider class is required.

The UI component datasource:

```text
mladmincontactus_contactus_listing_data_source
```

is mapped to the SearchResult collection through `CollectionFactory`.

This keeps the implementation aligned with Magento's standard UI listing architecture.

---

# Contact Us Submission Flow

The module integrates with:

```php
Magento\Contact\Controller\Index\Post
```

through:

```text
Plugin/ContactusPlugin.php
```

The Magento core Contact Us controller remains untouched.

This is preferable to using a preference/controller override because:

- Core code is not modified.
- Magento upgrades are easier.
- The customization is isolated.
- The module follows Magento's interception architecture.

The plugin captures the submitted contact information and persists it using:

```text
ContactusFactory
        ↓
Contactus Model
        ↓
Contactus ResourceModel
```

The stored fields are:

```text
name
email
telephone
comment
store_id
created_at
```

---

# Admin Grid

The Admin grid is located under the configured Contact Us Admin menu.

The grid provides:

- ID
- Name
- Email
- Telephone
- Comment
- Store ID
- Submitted At

Example:

```text
+----+-----------+----------------------+------------+-------------+----------+---------------------+
| ID | Name      | Email                | Telephone  | Comment     | Store ID | Submitted At        |
+----+-----------+----------------------+------------+-------------+----------+---------------------+
| 12 | John Doe  | john@example.com     | 123456789  | Hello       | 1        | 2026-10-27 10:30:00 |
| 11 | Jane Doe  | jane@example.com     | 987654321  | Information | 1        | 2026-10-27 09:15:00 |
+----+-----------+----------------------+------------+-------------+----------+---------------------+
```

---

# Grid Features

The grid supports:

### Sorting

The ID column is sorted descending by default so that the latest submissions appear first.

### Filtering

Available filters include:

- ID
- Name
- Email
- Telephone
- Comment
- Store ID
- Submitted At

### Pagination

Magento's standard UI pagination component is used.

### Bookmarks

The grid supports Magento UI bookmarks so administrators can save and restore grid column/filter configurations.

### Column Controls

Administrators can control which columns are visible.

### Column Resizing

Columns can be resized from the Admin grid.

### Delete

Individual submissions can be deleted.

### Mass Delete

Multiple submissions can be selected and deleted using the grid mass action.

---

# Read-Only Design

Contact Us submissions are treated as historical customer submissions.

Therefore, the grid intentionally does **not** provide:

- Add submission
- Edit submission
- Inline editing
- Save submission
- New submission form

The Admin grid is intended for:

```text
View
Filter
Sort
Search
Delete
Mass Delete
```

rather than modifying customer-submitted data.

This avoids unnecessary CRUD functionality and keeps the module's responsibility focused.

---

# ACL

The module defines the following ACL resources:

```text
Magelearn_Contactus::contactus
    ├── Magelearn_Contactus::manage
    └── Magelearn_Contactus::delete
```

### Manage Submissions

```text
Magelearn_Contactus::manage
```

Controls access to the Contact Us Admin grid.

### Delete Submission

```text
Magelearn_Contactus::delete
```

Controls deletion of Contact Us submissions.

---

# Database Schema

The module uses Magento's declarative schema:

```text
etc/db_schema.xml
```

instead of legacy:

```text
InstallSchema.php
UpgradeSchema.php
```

The primary table is:

```text
magelearn_contactus
```

Typical fields include:

```text
id
name
email
telephone
comment
store_id
created_at
```

Declarative schema makes database changes easier to maintain across Magento upgrades.

---

# Installation

Enable the module:

```bash
bin/magento module:enable Magelearn_Contactus
```

Run database upgrade:

```bash
bin/magento setup:upgrade
```

Clean cache:

```bash
bin/magento cache:clean
```

---

# Production Deployment

For production mode:

```bash
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

---

# Development / DI Troubleshooting

If Magento reports stale generated classes after changing DI configuration, remove generated code and metadata:

```bash
rm -rf generated/code generated/metadata
```

Then:

```bash
bin/magento setup:di:compile
bin/magento cache:clean
```

To verify the Contact Us controller plugin:

```bash
bin/magento dev:di:info "Magento\Contact\Controller\Index\Post"
```

---

# Existing Installation / Migration

If this module replaces an older version that used:

```text
InstallSchema.php
UpgradeSchema.php
```

or an existing `setup_version`, review the existing database before deployment.

Do **not** blindly drop:

```text
magelearn_contactus
```

if it already contains production submissions.

The migration should preserve existing records and reconcile the existing database structure with:

```text
etc/db_schema.xml
```

before deploying to production.

---

# Directory Structure

Recommended module structure:

```text
Magelearn/Contactus/
│
├── Controller/
│   └── Adminhtml/
│       └── Contactus/
│           ├── Delete.php
│           ├── Index.php
│           └── MassDelete.php
│
├── Model/
│   ├── Contactus.php
│   └── ResourceModel/
│       ├── Contactus.php
│       └── Contactus/
│           └── Collection.php
│
├── Plugin/
│   └── ContactusPlugin.php
│
├── etc/
│   ├── acl.xml
│   ├── db_schema.xml
│   ├── di.xml
│   ├── module.xml
│   │
│   └── adminhtml/
│       ├── di.xml
│       ├── menu.xml
│       └── routes.xml
│
├── view/
│   └── adminhtml/
│       ├── layout/
│       │   └── mladmincontactus_contactus_index.xml
│       │
│       └── ui_component/
│           └── mladmincontactus_contactus_listing.xml
│
├── composer.json
├── registration.php
└── README.md
```

> **Note:** `Model/ResourceModel/Contactus/Grid/Collection.php` should not be required when the Admin grid uses Magento's `SearchResult` virtual type.

---

# Magento Design Principles Used

This module demonstrates several Magento 2 architectural principles:

### 1. Dependency Injection

Dependencies are injected through constructors rather than instantiated using the ObjectManager.

### 2. Service/Model Separation

Database persistence is handled by the ResourceModel rather than directly from controllers.

### 3. Plugin Instead of Preference

The Magento Contact Us controller is extended through interception instead of replacing the core controller.

### 4. Declarative Schema

Database structure is defined through:

```text
etc/db_schema.xml
```

### 5. UI Component DataProvider

The Admin grid uses Magento's standard UI DataProvider.

### 6. SearchResult for Admin Grids

The UI grid uses:

```php
Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
```

rather than treating a normal domain collection as a UI search result.

### 7. Single Responsibility

Each layer has a focused responsibility:

```text
Plugin       → integrate with Contact Us flow
Model        → represent submission
ResourceModel → persist submission
Collection   → retrieve domain models
SearchResult  → provide Admin grid data
DataProvider → expose data to UI component
Controller   → handle Admin actions
ACL          → control permissions
```

---

# License

OSL-3.0
