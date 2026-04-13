SELECT 
    department,
    name,
    salary,
    DENSE_RANK() OVER (
        PARTITION BY department 
        ORDER BY salary DESC
    ) AS dept_rank
FROM employees;