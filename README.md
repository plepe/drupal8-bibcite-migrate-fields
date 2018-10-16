Copy the old biblio table to the new database - we need this to match old node ids to new bibcite reference ids.
```sh
mysqldump drupal6 biblio | mysql drupal8
```
