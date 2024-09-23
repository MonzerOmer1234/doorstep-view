# Apartment Search API

This API allows users to search for available apartments based on various filters. It is built using Laravel and provides endpoints to retrieve apartment listings.

## Table of Contents

- [Getting Started](#getting-started)
- [API Endpoints](#api-endpoints)
  - [Search Apartments](#search-apartments)
- [Filters](#filters)
- [Response Format](#response-format)
- [Error Handling](#error-handling)

## Getting Started

To get started with the API, ensure you have the following:

- PHP >= 7.4
- Composer
- Laravel

Clone the repository and install the dependencies:

```bash
git clone https://github.com/MonzerOmer1234/doorstep-view
cd doorstep-view
composer install
```

Run migrations to set up the database:

```bash
php artisan migrate
```

Start the local server:

```bash
php artisan serve
```

The API will be available at `http://localhost:8000`.

## API Endpoints

### Search Apartments

**GET** `/api/apartments/search`

This endpoint allows you to search for apartments based on various filters.

#### Example Request

You can use Postman or curl to test the endpoint.

**Request URL:**
```
GET http://localhost:8000/api/apartments/search
```

**Query Parameters:**
- `title`: (optional) Search apartments with a title containing this string.
- `price_min`: (optional) Minimum price of the apartment.
- `price_max`: (optional) Maximum price of the apartment.
- `rooms`: (optional) Number of rooms in the apartment.
- `available`: (optional) Boolean value to filter available apartments (`true` or `false`).
- `area`: (optional) Area of the apartment.
- `building_age`: (optional) Maximum age of the building.

#### Example with Filters

```http
GET http://localhost:8000/api/apartments/search?price_min=500&price_max=1500&rooms=2&available=true
```

## Filters

You can combine multiple filters to narrow down the search results:

- **Title**: Use this to search for apartments with specific keywords.
- **Price**: Use `price_min` and `price_max` to set a price range.
- **Rooms**: Filter based on the number of rooms.
- **Availability**: Use `available` to filter by availability status.
- **Area**: Filter based on the area size.
- **Building Age**: Filter apartments based on the maximum allowed age of the building.

## Response Format

The API responds with a JSON object. On a successful request, the response will be an array of apartments or a message if no matches are found.

### Example Successful Response

```json
[
    {
        "id": 1,
        "title": "Spacious Apartment",
        "description": "A lovely spacious apartment in the city center.",
        "price": 1200,
        "address": "123 Main St, City, Country",
        "available": true,
        "rooms": 2,
        "area": 850,
        "building_age": 5
    }
]
```

### Example Response for No Matches

```json
{
    "message": "No matches found"
}
```

## Error Handling

If the request fails due to a server error, the API will respond with an appropriate error message:

### Example Error Response

```json
{
    "error": "Internal Server Error"
}
```

## Conclusion

This API provides a flexible way to search for available apartments. Feel free to explore the various filters to find the apartment that suits your needs!
```

Feel free to modify any sections as necessary!