--select por ano
SELECT i."PRODUTO" as produto, i."CODIGO" as codigo,i."PRECOVENDA" as preco_venda ,i."PRECOCUSTO" as preco_custo,
r."NUMERO" as numero, v."CODNOTA" as codnota, EXTRACT(YEAR FROM r."DATA") as ano ,
SUM(i."PRECOVENDA"*v."QTDE") as valor_total, SUM(v."QTDE") AS total_vendido
FROM "C000062" as v
JOIN "C000025" as i ON v."CODPRODUTO" = i."CODIGO"
JOIN "C000061" as r ON r."NUMERO" = v."CODNOTA"
where (v."QTDE") > 1 and EXTRACT(YEAR FROM r."DATA") = '2022'
GROUP BY i."PRODUTO",r."DATA",r."TOTAL_NOTA",r."NUMERO",v."CODNOTA",i."PRECOVENDA",i."PRECOCUSTO",i."CODIGO"
ORDER BY ano,total_vendido  DESC

--select por dia
SELECT i."PRODUTO" as produto,i."CODIGO" as codigo,r."NUMERO" as numero, v."CODNOTA" as codnota,r."DATA" as data ,
SUM(i."PRECOVENDA"*v."QTDE") as valor_total, SUM(v."QTDE") AS total_vendido
FROM "C000062" as v
JOIN "C000025" as i ON v."CODPRODUTO" = i."CODIGO"
JOIN "C000061" as r ON r."NUMERO" = v."CODNOTA"
WHERE (v."QTDE") > 1 AND DATE(r."DATA") = '2023-05-16'
GROUP BY i."PRODUTO",r."DATA",r."TOTAL_NOTA",r."NUMERO",v."CODNOTA",i."CODIGO"
ORDER BY total_vendido  DESC
