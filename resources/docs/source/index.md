---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->

#Auth

## api/auth/login

> Example response:

```json
{
    "data": {
        "message": "You have logged in successfully",
        "status_code": 200
    }
}
```

### HTTP Request
`POST api/auth/login`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
email | string |  required  | 
password | string |  required  |

## api/auth/logout

> Example response:

```json
{
    "data": {
        "message": "You have logged out successfully",
        "status_code": 200
    }
}
```

### HTTP Request
`POST api/auth/logout`

## api/auth/create-testing-token

> Example response:

```json
{
    "data": {
        "access_token": "sSM1zRxzfpfIvBRe9d1XzfETZlwvGyDXqK94BOhi8eTc3sXF34JU7qKVVGemwzv8ZcnsNbOLzLFnXYZ",
        "status_code": 200
    }
}
```

### HTTP Request
`POST api/auth/create-testing-token`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
email | string |  required  | 
password | string |  required  |

## api/auth/forgot-password

> Example response:

```json
{
}
```
### HTTP Request
`POST api/auth/forgot-password`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
email | string |  required  |

## api/auth/reset-password

> Example response:

```json
{
}
```
### HTTP Request
`POST api/auth/reset-password`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
token | string |  required  |
email | string |  required and email |
password | string |  required  |
password_confirmation | string |  required  |

## api/auth/change-password

> Example response:

```json
{

}
```

### HTTP Request
`POST api/auth/change-password`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
old_password | string |  required  | 
new_password | string |  required  |
new_password_confirmation | string |  required  |

# Errors

B5-Spark uses conventional HTTP response codes to indicate the success or failure of an API request. The table below contains a summary of the typical response codes

| Code | Description           
| ------------- |:-------------
| 200 | Everything is ok. 
| 400 | Valid data was given but the request has failed.      
| 401 | No valid API Key was given.
| 404 | The request resource could not be found.
| 419 | Invalid token or session expired.
| 422 | The payload has missing required parameters or invalid data was given.
| 429 | Too many attempts.
| 500 | Request failed due to an internal error .
| 503 | Server is offline for maintenance     

#Users

## api/users

> Example (DataTables) response:

```json
{
    "draw": 0,
    "recordsTotal": 1,
    "recordsFiltered": 1,
    "data": [
      {
        "id": "1",
        "name": "admin",
        "email": "admin@et-spark.com",
        "created_at": "2017-12-26 08:50:27",
        "updated_at": "2017-12-26 11:31:27"
      }
    ]
}
```

### HTTP Request
`GET api/users`

## api/users/{id}

> Example response:

```json
{
    "data": {
        "id": 31,
        "name": "admin",
        "email": "admin@et-spark.com",
        "profile_picture_path": null,
        "role": {
            "id": 1,
            "name": "Super Admin",
            "code": "super_admin",
            "guard_name": "web",
            "created_at": "2020-01-21 18:15:29",
            "updated_at": "2020-01-21 18:15:29",
            "pivot": {
                "model_id": 31,
                "role_id": 1,
                "model_type": "App\\Models\\User"
            }
        }
    }
}
```

### HTTP Request
`GET api/users/{id}`

## api/users

> Example response:

```json
{
    "data": {
        "message": "User have created successfully",
        "data": null,
        "status_code": 200
    }
}
```
### HTTP Request
`POST api/users`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
name | string |  required  |
email | string |  required and email |
password | string |  required  |
password_confirmation | string |  required  |
profile_picture | file |  optional  | jpeg,jpg,bmp,png,gif
role_id | integer |  required  | 


## api/users/{id}

> Example response:

```json
{
    "data": {
        "message": "User updated successfully",
        "data": {
            "id": 27,
            "name": "First Name",
            "email": "hello3@test.come",
            "profile_picture_path": "http://test.localhost:8000/images/users/profile_pictures/usertesting-ZYnZw5jiMb.jpg",
            "role": {
                "id": 1,
                "name": "Super Admin",
                "code": "super_admin",
                "guard_name": "web",
                "created_at": "2020-01-21 18:15:29",
                "updated_at": "2020-01-21 18:15:29",
                "pivot": {
                    "model_id": 27,
                    "role_id": 1,
                    "model_type": "App\\Models\\User"
                }
            }
        },
        "status_code": 200
    }
}
```
### HTTP Request
`PUT api/users/{id}`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
name | string |  required  |
email | string |  required and email |
profile_picture | file |  optional  | jpeg,jpg,bmp,png,gif
role_id | integer |  required  | 

## api/users/{id}

> Example response:

```json
{
    "data": {
        "message": "User deleted successfully",
        "data": null,
        "status_code": 200
    }
}
```
### HTTP Request
`DELETE api/users/{id}`

## api/users/me/removeProfilePicture

> Example response:

```json
{
    "data": {
        "message": "Picture was removed successfully",
        "status_code": 200
    }
}
```
### HTTP Request
`PUT api/users/me/removeProfilePicture`


## api/users/me

#### Logged in user profile
> Example response:

```json
{
    "data": {
        "id": 31,
        "name": "admin",
        "email": "admin@et-spark.com",
        "profile_picture_path": null,
        "role": {
            "id": 1,
            "name": "Super Admin",
            "code": "super_admin",
            "guard_name": "web",
            "created_at": "2020-01-21 18:15:29",
            "updated_at": "2020-01-21 18:15:29",
            "pivot": {
                "model_id": 31,
                "role_id": 1,
                "model_type": "App\\Models\\User"
            }
        }
    }
}
```
### HTTP Request
`GET api/users/me`

## api/users/me

#### Logged in user profile update.
> Example response:

```json
{
    "data": {
        "message": "User updated successfully",
        "data": {
            "id": 27,
            "name": "First Name",
            "email": "hello3@test.come",
            "profile_picture_path": "http://test.localhost:8000/images/users/profile_pictures/usertesting-ZYnZw5jiMb.jpg",
            "role": {
                "id": 1,
                "name": "Super Admin",
                "code": "super_admin",
                "guard_name": "web",
                "created_at": "2020-01-21 18:15:29",
                "updated_at": "2020-01-21 18:15:29",
                "pivot": {
                    "model_id": 27,
                    "role_id": 1,
                    "model_type": "App\\Models\\User"
                }
            }
        },
        "status_code": 200
    }
}
```
### HTTP Request
`PUT api/users/me`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
name | string |  required  |
email | string |  required and email |
profile_picture | file |  optional  | jpeg,jpg,bmp,png,gif


# Roles

## Get All Roles
> Example response:

```json
{
    "data": [
        {
            "id": 1,
            "name": "Super Admin",
            "code": "super_admin",
            "guard_name": "web",
            "created_at": "2020-01-21 18:15:29",
            "updated_at": "2020-01-21 18:15:29"
        }
    ]
}
```

### HTTP Request
`GET api/roles`


# Lookups

##Get All Lookup Categories
> Example response:

```json
{
    "data": [
        {
            "id": 1,
            "code": "customer_types",
            "name": "Customer Types",
            "created_at": "20-05-2020 08:32:30",
            "updated_at": "20-05-2020 08:32:30"
        },
        {
            "id": 2,
            "code": "contract_statuses",
            "name": "Contract Statuses",
            "created_at": "20-05-2020 08:32:30",
            "updated_at": "20-05-2020 08:32:30"
        }
    ]
}
```

### HTTP Request
`GET api/lookups/categories`


##Get All Lookups
> Example response:

```json
{
    "data": [
        {
            "id": 2,
            "code": "corporate",
            "name": "Corporate",
            "value": "2",
            "extra_details": null,
            "model_type": "App\\Models\\Customer\\Customer",
            "category_id": 2,
            "is_active": 1,
            "is_system": 0,
            "created_at": "20-05-2020 08:33:11",
            "updated_at": "20-05-2020 08:33:11"
        }
    ]
}
```

### HTTP Request
`GET api/lookups/getAll`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
search | string |  optional  |
category_id | integer |  optional |
category_code | string |  optional  | 


##Add New Lookup
> Example response:

```json
{
    "data": {
        "message": "Lookups have created successfully",
        "data": null,
        "status_code": 200
    }
}
```

### HTTP Request
`POST api/lookups`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
name | string |  required  | max 100, unique
category_id | integer |  required |
value | string |  required  | max 100


##Update Lookup
> Example response:

```json
{
    "data": {
        "message": "Lookups updated successfully",
        "data": {
            "id": 3,
            "code": "testing_add",
            "name": "testing",
            "value": "5",
            "extra_details": null,
            "model_type": "App\\Models\\Customer\\Customer",
            "category_id": "1",
            "is_active": 1,
            "is_system": 0,
            "created_at": "20-05-2020 09:50:19",
            "updated_at": "20-05-2020 09:52:26"
        },
        "status_code": 200
    }
}
```

### HTTP Request
`PUT api/lookups/3`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
name | string |  required  | max 100, unique
category_id | integer |  required |


##Get Lookup details
> Example response:

```json
{
    "data": {
        "id": 3,
        "code": "testing_add",
        "name": "testing",
        "value": "5",
        "extra_details": null,
        "model_type": "App\\Models\\Customer\\Customer",
        "category_id": 1,
        "is_active": 1,
        "is_system": 0,
        "created_at": "20-05-2020 09:50:19",
        "updated_at": "20-05-2020 09:52:26"
    }
}
```

### HTTP Request
`GET api/lookups/3`


##Get Lookup Datatable
> Example response:

```json
{
    "draw": 0,
    "recordsTotal": 1,
    "recordsFiltered": 1,
    "data": [
        {
            "id": "2",
            "code": "corporate",
            "name": "Corporate",
            "value": "2",
            "extra_details": null,
            "model_type": "App\\Models\\Customer\\Customer",
            "category_id": "2",
            "is_active": "1",
            "is_system": 0,
            "created_at": "20-05-2020 08:33:11",
            "updated_at": "20-05-2020 08:33:11",
            "category": {
                "id": "2",
                "code": "contract_statuses",
                "name": "Contract Statuses",
                "created_at": "20-05-2020 08:32:30",
                "updated_at": "20-05-2020 08:32:30"
            }
        }
    ],
    "queries": [
        {
            "query": "select count(*) as aggregate from (select '1' as `row_count` from `lookups` where `category_id` = ? and `is_system` = ?) count_row_table",
            "bindings": [
                "2",
                ""
            ],
            "time": "10.68"
        },
        {
            "query": "select * from `lookups` where `category_id` = ? and `is_system` = ?",
            "bindings": [
                "2",
                ""
            ],
            "time": "0.35"
        },
        {
            "query": "select * from `lookup_categories` where `lookup_categories`.`id` in (2)",
            "bindings": [],
            "time": "0.48"
        }
    ],
    "input": {
        "category_id": "2"
    }
}
```

### HTTP Request
`GET api/lookups/getAll/datatable`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
category_id | integer |  optional |