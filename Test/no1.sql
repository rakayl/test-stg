SELECT 
    customer_id,
    SUM(amount) AS total_amount
FROM sales
GROUP BY customer_id
ORDER BY total_amount DESC
LIMIT 1;