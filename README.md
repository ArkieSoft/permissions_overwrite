# permissions_overwrite

Allow overwriting external storage permissions

## Usage

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
