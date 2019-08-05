地铁
before:
https://public.wutongwan.org/public-20180621-Fr6qNbdlyrPuVobWWVDrcCIIOqQh
new:
https://public.wutongwan.org/public-20180621-FqxhNzKvq8UemIF3k5eEJVnQlQ-P
小区
before:
https://public.wutongwan.org/public-20180621-FoI7IGGEikC3f3is5mKuMG40YUyZ
new:
https://public.wutongwan.org/public-20180621-FmMgCDDpfej5y4oLnqXlwBJR2ZnA
房屋
before:
https://public.wutongwan.org/public-20180621-Ft2scumka2NEpDkYQ8Wr8mfKJA09
new:
https://public.wutongwan.org/public-20180621-FsQEuWedmm3OYDkkISu0B5HWBX4A

select id,room_number from Laputa.rooms where suite_id = 98319;
SELECT customer_id FROM Laputa.contract_with_customers WHERE stage = '执行中' and status = '已签约' and room_id = 266196 ORDER BY id DESC LIMIT 1

select search_text,price,month_price from Laputa.rooms where code = '23061-D';

select is_month from Laputa.suites where id = 23061;


'官网线上','椋鸟计划线下1','椋鸟计划线下2'