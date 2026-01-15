# Extendable Order & Payment Management API (EOPM)

## Important Links
  - [Installation](docs/EOPM_Docker_Installation_Guide.md)
  - [Architecture](docs/payment_gateway_architecture.md)

## Overview

The Extendable Order & Payment Management API (EOPM) is a RESTful API built with Laravel 12, secured using JWT authentication, and designed with extensibility in mind. The system allows managing orders and processing payments through multiple gateways using a strategy-based architecture.

## Base URL

```
http://localhost:8090/
```

## Authentication

The API uses JWT (JSON Web Token) bearer authentication. Include the access token in the Authorization header for protected endpoints:

```
Authorization: Bearer {access_token}
```

---

## API Endpoints

### System

#### Heartbeat API

**Endpoint:** `GET /api/auth/heartbeat`

**Description:** Check if the service is alive and responding.

**Authentication:** Not required

**Response:**

- **200 OK** - Service is alive
```json
{
  "status": "success",
  "status_code": 200,
  "message": "Service is alive",
  "data": null,
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768220894
  }
}
```

- **404 Not Found** - Service not found
```json
{
  "status": "warning",
  "status_code": 404,
  "message": "not found",
  "data": null,
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768222481
  }
}
```

---

### Authentication

#### Login

**Endpoint:** `POST /api/auth/login`

**Description:** Authenticate user and receive access token. Invalidates all other sessions.

**Authentication:** Not required

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| email | string | Yes | User email address |
| password | string | Yes | User password |

**Example Request:**
```
POST /api/auth/login
Content-Type: multipart/form-data

email=admin1@example.com
password=password123
```

**Responses:**

- **200 OK** - Login successful
```json
{
  "status": "success",
  "status_code": 200,
  "message": "Login successful. All other sessions have been invalidated (If Exist).",
  "data": {
    "id": 1,
    "name": "Admin User",
    "email": "admin1@example.com",
    "phone": "+201234567890",
    "status": "active",
    "roles": ["Administrator"],
    "address": "14 Samir Sayed Ahmed",
    "city": {
      "id": 1,
      "name": "Cairo"
    },
    "country": {
      "id": 1,
      "name": "Egypt"
    },
    "created_at": "15-Jan-2026 11:16 am",
    "updated_at": "15-Jan-2026 13:57 pm",
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  },
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768485421
  }
}
```

- **403 Forbidden** - Invalid credentials
```json
{
  "status": "warning",
  "status_code": 403,
  "message": "Invalid Email or Password",
  "data": null,
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768485850
  }
}
```

- **422 Unprocessable Entity** - Validation error
```json
{
  "status": "error",
  "status_code": 422,
  "message": "Please provide a valid email address.",
  "data": null,
  "pagination": null,
  "errors": {
    "email": ["Please provide a valid email address."]
  },
  "meta": {
    "server_time": 1768485543
  }
}
```

#### Register Customer

**Endpoint:** `POST /api/auth/customer/register`

**Description:** Create a new customer account.

**Authentication:** Not required

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | Yes | Customer name |
| email | string | Yes | Customer email address |
| password | string | Yes | Customer password |
| password_confirmation | string | Yes | Password confirmation |
| phone | string | Yes | Customer phone number |
| address | string | Yes | Customer address |
| city_id | integer | Yes | City ID |
| country_id | integer | Yes | Country ID |
| status | string | No | Account status (default: active) |

**Example Request:**
```
POST /api/auth/customer/register
Content-Type: multipart/form-data

name=Customer 3
email=customer3@example.com
password=password123
password_confirmation=password123
phone=+20103302015
address=magic st
city_id=2
country_id=1
status=active
```

**Responses:**

- **200 OK** - Registration successful
```json
{
  "status": "success",
  "status_code": 200,
  "message": "User registered successfully, you can login with the created account now",
  "data": {
    "id": 14,
    "name": "Customer 2",
    "email": "customer2@example.com",
    "phone": "+20103302015",
    "status": "active",
    "roles": ["Customer"],
    "address": "magic st",
    "city": {
      "id": 2,
      "name": "Alexandria"
    },
    "country": {
      "id": 1,
      "name": "Egypt"
    },
    "created_at": "13-Jan-2026 08:31 am",
    "updated_at": "13-Jan-2026 08:31 am",
    "access_token": null
  },
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768293108
  }
}
```

- **422 Unprocessable Entity** - Validation error
```json
{
  "status": "error",
  "status_code": 422,
  "message": "This email is already registered.",
  "data": null,
  "pagination": null,
  "errors": {
    "email": ["This email is already registered."]
  },
  "meta": {
    "server_time": 1768492336
  }
}
```

#### Register Administrator

**Endpoint:** `POST /api/auth/admin/register`

**Description:** Create a new administrator account. Requires administrator privileges.

**Authentication:** Required (Bearer token)

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | Yes | Administrator name |
| email | string | Yes | Administrator email address |
| password | string | Yes | Administrator password |
| password_confirmation | string | Yes | Password confirmation |
| phone | string | Yes | Administrator phone number |
| address | string | Yes | Administrator address |
| city_id | integer | Yes | City ID |
| country_id | integer | Yes | Country ID |
| status | string | No | Account status (default: active) |

**Example Request:**
```
POST /api/auth/admin/register
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
Content-Type: multipart/form-data

name=Admin 3
email=admin3@example.com
password=password123
password_confirmation=password123
phone=+20103302015
address=magic st
city_id=2
country_id=1
status=active
```

**Responses:**

- **200 OK** - Registration successful
```json
{
  "status": "success",
  "status_code": 200,
  "message": "User registered successfully, you can login with the created account now",
  "data": {
    "id": 16,
    "name": "Admin 2",
    "email": "admin2@example.com",
    "phone": "+20103302015",
    "status": "active",
    "roles": ["Administrator"],
    "address": "magic st",
    "city": {
      "id": 2,
      "name": "Alexandria"
    },
    "country": {
      "id": 1,
      "name": "Egypt"
    },
    "created_at": "13-Jan-2026 09:00 am",
    "updated_at": "13-Jan-2026 09:00 am",
    "access_token": null
  },
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768294856
  }
}
```

- **403 Forbidden** - Insufficient permissions
```json
{
  "status": "warning",
  "status_code": 403,
  "message": "forbidden",
  "data": null,
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768493438
  }
}
```

---

### Inventory

#### Products List

**Endpoint:** `GET /api/v1/inventory/products/list`

**Description:** Get paginated list of products.

**Authentication:** Required (Bearer token)

**Query Parameters:**

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| page | integer | No | 1 | Page number |
| limit | integer | No | 10 | Items per page |

**Example Request:**
```
GET /api/v1/inventory/products/list?page=2&limit=10
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

**Responses:**

- **200 OK** - Products retrieved successfully
```json
{
  "status": "success",
  "status_code": 200,
  "message": "Products loaded successfully",
  "data": [
    {
      "id": 11,
      "name": "Nintendo Switch OLED",
      "price": "349.99",
      "stock_qty": 45
    },
    {
      "id": 12,
      "name": "PlayStation 5",
      "price": "499.99",
      "stock_qty": 12
    }
  ],
  "pagination": {
    "current_page": 2,
    "per_page": 10,
    "total_records": 26,
    "current_records": 10,
    "total_pages": 3,
    "has_next": true,
    "has_previous": true
  },
  "errors": null,
  "meta": {
    "server_time": 1768496575
  }
}
```

- **403 Forbidden** - Insufficient permissions
```json
{
  "status": "warning",
  "status_code": 403,
  "message": "forbidden",
  "data": null,
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768494949
  }
}
```

#### Product By ID

**Endpoint:** `GET /api/v1/inventory/products/{id}`

**Description:** Get details of a specific product.

**Authentication:** Required (Bearer token)

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | Product ID |

**Example Request:**
```
GET /api/v1/inventory/products/12
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

**Responses:**

- **200 OK** - Product retrieved successfully
```json
{
  "status": "success",
  "status_code": 200,
  "message": "Product loaded successfully",
  "data": {
    "id": 12,
    "name": "PlayStation 5",
    "price": "499.99",
    "stock_qty": 12
  },
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768496972
  }
}
```

- **403 Forbidden** - Insufficient permissions

#### Add Product

**Endpoint:** `POST /api/v1/inventory/products/add`

**Description:** Add a new product to inventory.

**Authentication:** Required (Bearer token)

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | Yes | Product name |
| price | number | Yes | Product price |
| stock_qty | integer | Yes | Stock quantity |

**Example Request:**
```
POST /api/v1/inventory/products/add
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
Content-Type: multipart/form-data

name=Kindle Paperwhite
price=139.99
stock_qty=65
```

**Responses:**

- **200 OK** - Product added successfully
```json
{
  "status": "success",
  "status_code": 200,
  "message": "Product added successfully",
  "data": {
    "id": 27,
    "name": "Kindle Paperwhite",
    "price": "139.99",
    "stock_qty": "65"
  },
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768496358
  }
}
```

- **403 Forbidden** - Insufficient permissions

#### Delete Product

**Endpoint:** `DELETE /api/v1/inventory/products/{id}`

**Description:** Delete a product from inventory.

**Authentication:** Required (Bearer token)

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | Product ID |

**Example Request:**
```
DELETE /api/v1/inventory/products/25
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

**Responses:**

- **200 OK** - Product deleted successfully
```json
{
  "status": "success",
  "status_code": 200,
  "message": "Product has been deleted successfully",
  "data": {
    "id": 25,
    "name": "Kindle Paperwhite",
    "price": "139.99",
    "stock_qty": 65
  },
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768495935
  }
}
```

- **403 Forbidden** - Insufficient permissions
- **422 Unprocessable Entity** - Invalid product ID

---

### Orders

#### Add Order

**Endpoint:** `POST /api/v1/orders/add`

**Description:** Create a new order.

**Authentication:** Required (Bearer token)

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| product | integer | Yes | Product ID |
| qty | integer | Yes | Quantity to order |

**Example Request:**
```
POST /api/v1/orders/add
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
Content-Type: multipart/form-data

product=15
qty=2
```

**Responses:**

- **200 OK** - Order created successfully
```json
{
  "status": "success",
  "status_code": 200,
  "message": "New order added successfully",
  "data": {
    "order_id": 3,
    "user": {
      "id": 1,
      "name": "Admin User",
      "email": "admin1@example.com",
      "phone": "+201234567890",
      "status": "active",
      "address": "14 Samir Sayed Ahmed",
      "country_id": 1,
      "city_id": 1,
      "created_at": "15-Jan-2026 11:16 am",
      "updated_at": "15-Jan-2026 17:20 pm"
    },
    "status": "pending",
    "grand_total": "379.99$",
    "created_at": "15-Jan-2026 17:24 pm",
    "updated_at": "15-Jan-2026 17:24 pm",
    "items": [
      {
        "id": 3,
        "product": "KitchenAid Stand Mixer",
        "qty": 1,
        "price": "379.99",
        "order_id": 3,
        "created_at": "15-Jan-2026 17:24 pm",
        "updated_at": "15-Jan-2026 17:24 pm"
      }
    ],
    "payments": []
  },
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768497882
  }
}
```

- **403 Forbidden** - Insufficient permissions
- **422 Unprocessable Entity** - Validation errors (product doesn't exist, insufficient stock, etc.)

#### User Orders List

**Endpoint:** `GET /api/v1/orders/list`

**Description:** Get list of orders for the authenticated user.

**Authentication:** Required (Bearer token)

**Query Parameters:**

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| page | integer | No | 1 | Page number |
| limit | integer | No | 10 | Items per page |

**Example Request:**
```
GET /api/v1/orders/list?page=1&limit=10
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

**Responses:**

- **200 OK** - Orders retrieved successfully (includes full order details with items and payments)
- **403 Forbidden** - Insufficient permissions

#### All Orders List

**Endpoint:** `GET /api/v1/orders/all`

**Description:** Get list of all orders in the system (administrator only).

**Authentication:** Required (Bearer token with administrator role)

**Query Parameters:**

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| page | integer | No | 1 | Page number |
| limit | integer | No | 10 | Items per page |

**Example Request:**
```
GET /api/v1/orders/all?page=1&limit=10
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

**Responses:**

- **200 OK** - All orders retrieved successfully
- **403 Forbidden** - Insufficient permissions

#### Cancel Order

**Endpoint:** `PATCH /api/v1/orders/cancel/{id}`

**Description:** Cancel a pending order.

**Authentication:** Required (Bearer token)

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | Order ID |

**Example Request:**
```
PATCH /api/v1/orders/cancel/3
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

**Responses:**

- **200 OK** - Order cancelled successfully
```json
{
  "status": "success",
  "status_code": 200,
  "message": "Order has been cancelled",
  "data": {
    "order_id": 3,
    "status": "cancelled",
    "grand_total": "877.99$",
    "items": [],
    "payments": []
  },
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768500697
  }
}
```

- **403 Forbidden** - Insufficient permissions
- **422 Unprocessable Entity** - Order doesn't exist or not in pending status

#### Delete Order

**Endpoint:** `DELETE /api/v1/orders/delete/{id}`

**Description:** Delete an order.

**Authentication:** Required (Bearer token)

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | Order ID |

**Example Request:**
```
DELETE /api/v1/orders/delete/3
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

**Responses:**

- **200 OK** - Order deleted successfully
- **403 Forbidden** - Insufficient permissions
- **422 Unprocessable Entity** - Order doesn't exist

#### Delete Order Item

**Endpoint:** `DELETE /api/v1/orders/items/delete/{id}`

**Description:** Delete an item from an order.

**Authentication:** Required (Bearer token)

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | Order item ID |

**Example Request:**
```
DELETE /api/v1/orders/items/delete/1
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

**Responses:**

- **200 OK** - Order item deleted successfully
```json
{
  "status": "success",
  "status_code": 200,
  "message": "Order item has been deleted",
  "data": {
    "order_id": 2,
    "order_item": {
      "id": 2,
      "product": "KitchenAid Stand Mixer",
      "price": "379.99",
      "quantity": 1,
      "total_price": "379.99$"
    }
  },
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768501213
  }
}
```

- **403 Forbidden** - Insufficient permissions
- **422 Unprocessable Entity** - Order item doesn't exist

---

### Payments

#### Pay Order

**Endpoint:** `POST /api/v1/orders/pay`

**Description:** Process payment for an order using either Cash or MGateway payment methods.

**Authentication:** Required (Bearer token)

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| order_id | string | Yes | Order ID to pay |
| method | string | Yes | Payment method: "Cash" or "MGateway" |
| card[holder] | string | Conditional | Card holder name (required for MGateway) |
| card[card_number] | string | Conditional | Card number (required for MGateway) |
| card[cvv] | string | Conditional | Card CVV (required for MGateway) |
| card[valid] | string | Conditional | Card expiry date (required for MGateway) |

**Example Request (Cash Payment):**
```
POST /api/v1/orders/pay
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
Content-Type: multipart/form-data

order_id=1
method=Cash
```

**Example Request (Gateway Payment):**
```
POST /api/v1/orders/pay
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
Content-Type: multipart/form-data

order_id=1
method=MGateway
card[holder]=mahmoud salama
card[card_number]=4000000000000002
card[cvv]=777
card[valid]=20/28
```

**Responses:**

- **200 OK** - Cash payment successful
```json
{
  "status": "success",
  "status_code": 200,
  "message": "payment done",
  "data": {
    "payment_id": 17,
    "order_id": "1",
    "payer_name": "Admin User",
    "amount": "379.99",
    "method": "Cash",
    "transaction_id": "N/A",
    "card": "N/A",
    "status": "paid",
    "created_at": "15-Jan-2026 13:38 pm",
    "updated_at": "15-Jan-2026 13:38 pm"
  },
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768484320
  }
}
```

- **200 OK** - Gateway payment successful
```json
{
  "status": "success",
  "status_code": 200,
  "message": "payment done",
  "data": {
    "payment_id": 14,
    "order_id": "1",
    "payer_name": "mahmoud salama",
    "amount": "379.99",
    "method": "MGateway",
    "transaction_id": "demo_6968eb9ea512e5.80017393",
    "card": {
      "holder": "mahmoud salama",
      "card_number": "4000000000000002",
      "cvv": "777",
      "valid": "20/28"
    },
    "status": "paid",
    "created_at": "15-Jan-2026 13:29 pm",
    "updated_at": "15-Jan-2026 13:29 pm"
  },
  "pagination": null,
  "errors": null,
  "meta": {
    "server_time": 1768483742
  }
}
```

- **422 Unprocessable Entity** - Gateway payment failed
```json
{
  "status": "error",
  "status_code": 422,
  "message": "payment failed",
  "data": null,
  "pagination": null,
  "errors": {
    "payment": {
      "status": "declined",
      "card": {
        "holder": "mahmoud salama",
        "card_number": "4111111511111111",
        "cvv": "123",
        "valid": "12/29"
      },
      "transaction_id": "demo_6968e9c34aa4f0.46631903",
      "amount": 379.99,
      "status_code": 500
    }
  },
  "meta": {
    "server_time": 1768483267
  }
}
```

- **403 Forbidden** - Insufficient permissions

---

## Response Format

All API responses follow a consistent structure:

```json
{
  "status": "success|error|warning",
  "status_code": 200,
  "message": "Human readable message",
  "data": {},
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total_records": 100,
    "current_records": 10,
    "total_pages": 10,
    "has_next": true,
    "has_previous": false
  },
  "errors": null,
  "meta": {
    "server_time": 1768220894
  }
}
```

### Response Fields

- **status**: Response status indicator (success, error, warning)
- **status_code**: HTTP status code
- **message**: Human-readable message describing the response
- **data**: Response data (varies by endpoint)
- **pagination**: Pagination information (for list endpoints)
- **errors**: Error details (null if no errors)
- **meta**: Additional metadata (includes server timestamp)

---

## Error Handling

### Common Error Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource doesn't exist |
| 422 | Unprocessable Entity - Validation error |
| 500 | Internal Server Error |

### Error Response Example

```json
{
  "status": "error",
  "status_code": 422,
  "message": "Validation error message",
  "data": null,
  "pagination": null,
  "errors": {
    "field_name": [
      "Error message 1",
      "Error message 2"
    ]
  },
  "meta": {
    "server_time": 1768485543
  }
}
```

---

## Pagination

List endpoints support pagination with the following parameters:

- **page**: Page number (default: 1)
- **limit**: Items per page (default: 10)

Pagination information is included in the response:

```json
{
  "pagination": {
    "current_page": 2,
    "per_page": 10,
    "total_records": 26,
    "current_records": 10,
    "total_pages": 3,
    "has_next": true,
    "has_previous": true
  }
}
```

---

## Payment Gateway Integration

The API supports multiple payment methods through a strategy-based architecture:

### Supported Payment Methods

1. **Cash** - Direct cash payment
2. **MGateway** - Payment gateway integration

### Gateway Payment Test Cards

For testing MGateway payments, use these test card numbers:

| Card Number | CVV | Expiry | Expected Result |
|-------------|-----|--------|-----------------|
| 4000000000000002 | 777 | 20/28 | Success |
| 4111111111111111 | 123 | 12/29 | Insufficient balance |
| 4111111511111111 | 123 | 12/29 | Card declined |

---

## Security

### JWT Authentication

The API uses JWT tokens for authentication with the following characteristics:

- Token expiration: 1 hour
- Token is returned upon successful login
- Include token in Authorization header for protected endpoints
- Login invalidates all previous sessions

### Role-Based Access Control

The system implements role-based permissions:

- **Administrator**: Full access to all endpoints
- **Customer**: Limited access to customer-specific operations

---

## Rate Limiting

API rate limiting information will be added in future versions.

---

## Changelog

### Version 1.0.0 (Current)
- Initial API release
- Authentication endpoints
- Inventory management
- Order management
- Payment processing with Cash and MGateway methods

---

## Support

For API support and questions, please contact the development team.