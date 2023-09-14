# Restaurant Management

## Getting Started:
**Prerequisites:**
- Make sure you have Docker installed on your machine.
- Make sure port `9090` is free and not in use.

**Setup Steps:**
Inside project root folder Run
````
docker compose up -d --build
docker compose exec api bash
composer install && php artisan migrate --seed
````
The application is now accessible at `http://localhost:9090`.

---

## Applicaple Improvements:
- We would save the ingredients used for each ordered product in case there are any changes to the quantities of these product ingredients.
- Any changes to the inventory would be recorded.
- We would keep a record of the emails that have been sent.

---

## Action:
### Create an Order (`POST` `/api/orders`)

#### Request Body:
```json
{
    "products": [
        {
            "product_id": 1, // product id
            "quantity": 2 // quntity
        }
    ]
}
```
#### Responses:
##### Order created `201`:
```json
{
    "id": 3,
    "timestamp": 1694678285,
    "items": [
        {
            "id": 1,
            "name": "Burger",
            "quantity": 2,
            "ingredients": [
                {
                    "name": "Beef",
                    "amount": 150
                },
                {
                    "name": "Cheese",
                    "amount": 30
                },
                {
                    "name": "Onion",
                    "amount": 20
                }
            ]
        }
    ]
}
```

##### Validation errors `422`:
```json
{
    "message": "The products.0.product_id field is required.",
    "errors": {
        "products.0.product_id": [
            "The products.0.product_id field is required."
        ]
    }
}
```
```json
{
    "message": "No enough ingredients available to fulfill this product!",
    "errors": {
        "products.0.product_id": [
            "No enough ingredients available to fulfill this product!"
        ]
    }
}
```
```json
{
    "message": "The products.0.quantity field must be at least 1.",
    "errors": {
        "products.0.quantity": [
            "The products.0.quantity field must be at least 1."
        ]
    }
}