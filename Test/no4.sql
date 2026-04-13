SELECT 
    c.name AS customer_name,
    SUM(se.quantity * p.price) AS total_spent
FROM sales_extended se
JOIN customers c 
    ON se.customer_id = c.id
JOIN products p 
    ON se.product_id = p.id
GROUP BY c.name
ORDER BY total_spent DESC;