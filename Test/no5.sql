WITH daily_sales AS (
    SELECT 
        c.name AS customer_name,
        se.sale_date,
        SUM(se.quantity * p.price) AS daily_total
    FROM sales_extended se
    JOIN customers c ON se.customer_id = c.id
    JOIN products p ON se.product_id = p.id
    GROUP BY c.name, se.sale_date
)

SELECT 
    customer_name,
    sale_date,
    daily_total,
    SUM(daily_total) OVER (
        PARTITION BY customer_name 
        ORDER BY sale_date
    ) AS running_total
FROM daily_sales
ORDER BY customer_name, sale_date;