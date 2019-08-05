SELECT *
FROM Laputa.contract_with_customers
WHERE status = '已签约'
  AND stage = '执行中'
ORDER BY id DESC ;



SELECT *
FROM Laputa.humans
WHERE id IN
    (SELECT customer_id
     FROM Laputa.contract_with_customers
     WHERE status = '已签约'
       AND stage = '执行中'
     ORDER BY id DESC) ;
     confirmed