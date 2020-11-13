# permissions_overwrite

Allow overwriting external storage permissions.

This app allows overwriting what Nextcloud thinks the permission of a file or folder in an external storage is,
this can be useful in situations where Nextcloud improperly detects the permissions in an external storage.

This only affects what Nextcloud detects the permissions to be, the actual permissions from the external server still apply as normal.

## Usage

### Set a new overwrite

```bash
occ permissions_overwrite:set <mount_id> <path> <ALL|READONLY|NONE>
```

You can find the `mount_id` using `occ files_external:list`.

For example, to set the folder `Camera` in the storage mounted at `/Pictures` to read only:

```bash
$ occ files_external:list                                                                                                                                                     360ms  do 12 nov 2020 20:26:07 CET
+----------+-------------+---------+---------------------+------------------------------------+---------+------------------+-------------------+
| Mount ID | Mount Point | Storage | Authentication Type | Configuration                      | Options | Applicable Users | Applicable Groups |
+----------+-------------+---------+---------------------+------------------------------------+---------+------------------+-------------------+
| 17       | /Pictures   | Local   | None                | datadir: "\/data\/Pictures"        |         | All              |                   |
+----------+-------------+---------+---------------------+------------------------------------+---------+------------------+-------------------+

$ occ permissions_overwrite:set 17 Camera READONLY

```

Note that permission overwrites are recursive.

### Remove an overwrite

```bash
occ permissions_overwrite:remove <mount_id> <path>
```

### List existing overwrites

```bash
occ permissions_overwrite:list
```
