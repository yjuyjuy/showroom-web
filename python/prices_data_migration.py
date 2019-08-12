import mysql.connector, json, re
cnx = mysql.connector.connect(user='root', password='DwYeezy1218', database='laravel')
cursor = cnx.cursor(buffered=True)

cursor.execute('SELECT id, vendor_id, product_id, data, url, created_at, updated_at FROM prices WHERE deleted_at IS NULL')
for id, vendor_id, product_id, data, url, created_at, updated_at in cursor.fetchall():
	data_vendor_prices = (vendor_id, product_id, data, created_at, updated_at)
	cursor.execute("INSERT INTO vendor_prices(vendor_id, product_id, data, created_at, updated_at) VALUES(%s, %s, %s, %s, %s)", data_vendor_prices)
cnx.commit()
