# newtoncmvs2

Version 2 Solution

### ScheduledmaintenanceJob smj
- smj_id PRIM
- smjtsl_id 0..1 TimeSlot
- smjeng_id 0..1 Engineer
- smjmjb_id 0..1 MaintenanceJob

### smjcar
- smj_id 0.. ScheduledmaintenanceJob
- car_id 0.. Car

### TimeSlot tsl
- tsl_id PRIM
- tsl_starttime
- tsl_endtime

### Engineer eng
- eng_id PRIM
- eng_name

### Car car
- car_id PRIM
- carcmd_id 0..1 Model
- carcst_id 1..1 Customer
- car_license

### Customer cst
- cst_id PRIM
- cst_name

### CarModel mdl
- cmd_id PRIM
- cmdbrd_id 1..1 Brand
- cmd_name

### cmdspp
- cmd_id 0.. Model
- spp_id 0.. SparePart

### Brand brd
- brd_id PRIM
- brd_name 

### SparePart spp
- spp_id PRIM
- spp_name
- spp_costex
- spp_vat

### sppbrd
- spp_id 0.. SparePart
- brd_id 0.. Brand

### MaintenanceJob mjb
- mjb_id PRIM

### mjbspp
- mjb_id 0.. MaintenanceJob
- spp_id 0.. SparePart

===

See 