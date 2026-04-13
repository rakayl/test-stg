SELECT 
    o.id AS order_id,
    o.total_amount,
    p.paid_amount,
    CASE 
        WHEN p.paid_amount >= o.total_amount THEN 'Lunas'
        ELSE 'Belum Lunas'
    END AS status
FROM orders o
LEFT JOIN payments p 
    ON o.id = p.order_id;