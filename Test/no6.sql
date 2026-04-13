WITH product_sales AS (
    SELECT 
        p.category,
        p.name AS product_name,
        SUM(se.quantity) AS total_qty
    FROM sales_extended se
    JOIN products p ON se.product_id = p.id
    GROUP BY p.category, p.name
)

SELECT *
FROM (
    SELECT 
        category,
        product_name,
        total_qty,
        RANK() OVER (
            PARTITION BY category 
            ORDER BY total_qty DESC
        ) AS rank_in_category
    FROM product_sales
) ranked
WHERE rank_in_category <= 2
ORDER BY category, rank_in_category;