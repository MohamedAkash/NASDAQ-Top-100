from time import sleep
import requests
from pymongo import MongoClient
from bs4 import BeautifulSoup

url = 'https://finance.yahoo.com/most-active'
rounds = 0
while rounds < 5:
    response = requests.get(url)
    soup = BeautifulSoup(response.text, 'html.parser')

    thead = soup.table.thead
    tbody = soup.table.tbody

    headers = []
    data = []

    headers.append('_id')
    for header in thead.tr.children:
        headers.append(header.text)

    for index,tr in enumerate(tbody.children):
        data.append([])
        data[-1].append(index + 1)
        for value in tr.children:
            data[-1].append(value.text)

    client = MongoClient('localhost', 27017)
    db = client.Scraping
    collection = db.stock
    collection.delete_many({})

    for row in data:
        dataItem = dict()
        for i in range(len(headers)):
            if headers[i] in ('_id', 'Symbol', 'Name', 'Price (Intraday)', 'Change', 'Volume'):
                if headers[i] in ('Change', 'Price (Intraday)'):
                    row[i] = float(row[i])
                elif headers[i] == 'Volume':
                    row[i] = row[i][:-1]
                    row[i] = float(row[i])
                dataItem[headers[i]] = row[i]
        if dataItem:
            collection.insert_one(dataItem)

    sleep(180)
    rounds += 1