import mysql.connector, json, re
cnx = mysql.connector.connect(user='root', password='DwYeezy1218', database='laravel')
cursor = cnx.cursor(buffered=True)

cursor.execute('SELECT id, vendor_id, product_id, data, url, created_at, updated_at FROM prices WHERE deleted_at IS NULL')
for id, vendor_id, product_id, data, url, created_at, updated_at in cursor.fetchall():
	data = json.loads(data)
	offer_prices = dict()
	retail_prices = dict()
	for row in data:
		size = row['size']
		cost = int(re.match('[0-9]+',row['cost'])[0])
		resell = int(re.match('[0-9]+',str(row['resell']))[0])
		retail = int(re.match('[0-9]+',str(row['retail']))[0])
		offer_prices[size] = resell
		retail_prices[size] = retail
	data = json.dumps(data)
	offer_prices = json.dumps(offer_prices)
	retail_prices = json.dumps(retail_prices)
	if url is not None:
		url = json.dumps({'href':url})
	data_vendor_prices = (vendor_id, product_id, data, created_at, updated_at)
	cursor.execute("INSERT INTO vendor_prices(vendor_id, product_id, data, created_at, updated_at) VALUES(%s, %s, %s, %s, %s)", data_vendor_prices)
	data_offer_prices = (vendor_id, product_id, offer_prices, created_at, updated_at)
	cursor.execute("INSERT INTO offer_prices(vendor_id, product_id, prices, created_at, updated_at) VALUES(%s, %s, %s, %s, %s)", data_offer_prices)
	data_retail_prices = (vendor_id, product_id, retail_prices, url, created_at, updated_at)
	cursor.execute("INSERT INTO retail_prices(retailer_id, product_id, prices, link, created_at, updated_at) VALUES(%s, %s, %s, %s, %s, %s)", data_retail_prices)
cnx.commit()
