# Distribution of Inheritances app

# Start project

1. docker compose up
2. Enter the inheritanceDistributionApp service via console and run composer install

# Try Application

1. Example http request: 
   
>curl --location --request GET 'http://localhost:82/api/heritage/Borja' \
--header 'Content-Type: application/json' \
--data '{
"name": "Alan",
"birthday": "16-03-1923",
"properties": [
{
"type": "money",
"amount": 100000
}
],
"sons": [
{
"name": "Borja",
"birthday": "30-10-1943",
"properties": [],
"sons": [
{
"name": "David",
"birthday": "30-10-1963",
"properties": [],
"sons": [
{
"name": "Isac",
"birthday": "28-10-1983",
"properties": [],
"sons": []
},
{
"name": "Javier",
"birthday": "30-10-1981",
"properties": [],
"sons": []
}
]
},
{
"name": "Edu",
"birthday": "30-10-1964",
"properties": [],
"sons": []
},
{
"name": "Fernando",
"birthday": "30-10-1965",
"properties": [],
"sons": []
}
]
},
{
"name": "Cristian",
"birthday": "30-10-1942",
"properties": [],
"sons": [
{
"name": "Gabi",
"birthday": "30-10-1965",
"properties": [],
"sons": []
},
{
"name": "Hector",
"birthday": "30-10-1963",
"properties": [],
"sons": []
}
]
}
]
}'

2. Run tests:

>php bin/phpunit --coverage-text --coverage-clover=coverage.xml
