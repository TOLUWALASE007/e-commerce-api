# Admin User Flow Diagram

## 🔐 Authentication & Access Flow

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Admin Login   │───▶│  JWT Token      │───▶│  Admin Dashboard│
│   /login        │    │  Generation     │    │  /admin         │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ Email/Password  │    │ Admin Role      │    │ Protected Route │
│ Validation      │    │ Verification    │    │ Access Control  │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## 📊 Dashboard Navigation Flow

```
                    Admin Dashboard (/admin)
                           │
        ┌──────────────────┼──────────────────┐
        │                  │                  │
        ▼                  ▼                  ▼
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│ Statistics  │    │   Users     │    │  Products   │
│    Tab      │    │    Tab      │    │    Tab      │
└─────────────┘    └─────────────┘    └─────────────┘
        │                  │                  │
        ▼                  ▼                  ▼
┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│ Key Metrics │    │ User Table  │    │Product Table│
│- Total Users│    │- Name       │    │- Name       │
│- Products   │    │- Email      │    │- Price      │
│- Orders     │    │- Role       │    │- Stock      │
│- Revenue    │    │- Created    │    │- Rating     │
└─────────────┘    └─────────────┘    └─────────────┘
                           │
                           ▼
                  ┌─────────────┐
                  │   Orders    │
                  │    Tab      │
                  └─────────────┘
                           │
                           ▼
                  ┌─────────────┐
                  │ Order Table │
                  │- Order ID   │
                  │- Customer   │
                  │- Total      │
                  │- Status     │
                  │- Date       │
                  └─────────────┘
```

## 🔄 Product Management Flow

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│  View Products  │───▶│  Product Table  │───▶│  Product Actions│
│  (Products Tab) │    │  Display        │    │  Available      │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ Load Product    │    │ Show Product    │    │ Create Product  │
│ Data from API   │    │ Information:    │    │ (POST /products)│
│ GET /products   │    │ - Name          │    │                 │
└─────────────────┘    │ - Price         │    │ Update Product  │
                       │ - Stock         │    │ (PUT /products) │
                       │ - Rating        │    │                 │
                       └─────────────────┘    │ Delete Product  │
                                              │ (DELETE /products)│
                                              └─────────────────┘
```

## 👥 User Management Flow

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│  View Users     │───▶│   User Table    │───▶│  User Actions   │
│  (Users Tab)    │    │   Display       │    │  Available      │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ Load User Data  │    │ Show User Info: │    │ View User       │
│ from API        │    │ - Name          │    │ Details         │
│ GET /admin/users│    │ - Email         │    │                 │
└─────────────────┘    │ - Role          │    │ Manage Roles    │
                       │ - Created Date  │    │ (Admin/User)    │
                       └─────────────────┘    │                 │
                                              │ User Analytics  │
                                              └─────────────────┘
```

## 🛒 Order Management Flow

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│  View Orders    │───▶│   Order Table   │───▶│  Order Actions  │
│  (Orders Tab)   │    │   Display       │    │  Available      │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ Load Order Data │    │ Show Order Info:│    │ View Order      │
│ from API        │    │ - Order ID      │    │ Details         │
│ GET /admin/orders│   │ - Customer      │    │                 │
└─────────────────┘    │ - Total Amount  │    │ Update Status   │
                       │ - Status        │    │ (pending →      │
                       │ - Date          │    │  processing →   │
                       └─────────────────┘    │  completed)     │
                                              │                 │
                                              │ Order Analytics │
                                              └─────────────────┘
```

## 📈 Statistics Dashboard Flow

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│  Load Stats     │───▶│  Statistics     │───▶│  Key Metrics    │
│  (Default Tab)  │    │  Dashboard      │    │  Display        │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ API Call        │    │ Real-time Data  │    │ Visual Cards:   │
│ GET /admin/stats│    │ Processing      │    │ - Total Users   │
└─────────────────┘    └─────────────────┘    │ - Total Products│
                                              │ - Total Orders  │
                                              │ - Total Revenue │
                                              └─────────────────┘
```

## 🔐 Security & Error Handling Flow

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│  Admin Access   │───▶│  Authentication │───▶│  Authorization  │
│  Attempt        │    │  Check          │    │  Verification   │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ Valid Admin?    │    │ JWT Token       │    │ Admin Role      │
│                 │    │ Valid?          │    │ Confirmed?      │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         ▼                       ▼                       ▼
    ┌─────────┐              ┌─────────┐              ┌─────────┐
    │   NO    │              │   NO    │              │   NO    │
    └─────────┘              └─────────┘              └─────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ Redirect to     │    │ Token Refresh   │    │ Access Denied   │
│ Login Page      │    │ or Re-login     │    │ Message         │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## 🔄 Complete Admin Workflow

```
┌─────────────────┐
│   Admin Login   │
└─────────┬───────┘
          │
          ▼
┌─────────────────┐
│  Dashboard      │
│  Overview       │
└─────────┬───────┘
          │
    ┌─────┴─────┐
    │           │
    ▼           ▼
┌───────┐   ┌───────┐
│ Users │   │Products│
└───┬───┘   └───┬───┘
    │           │
    ▼           ▼
┌───────┐   ┌───────┐
│Manage │   │Manage │
│Users  │   │Products│
└───┬───┘   └───┬───┘
    │           │
    └─────┬─────┘
          │
          ▼
┌─────────────────┐
│   Orders        │
└─────────┬───────┘
          │
          ▼
┌─────────────────┐
│ Manage Orders   │
│ & Analytics     │
└─────────────────┘
```

## 📱 Mobile Responsive Flow

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│  Mobile Access  │───▶│  Responsive     │───▶│  Touch-Friendly │
│  /admin         │    │  Layout         │    │  Navigation     │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│ Collapsible     │    │ Mobile Tables   │    │ Swipe Gestures  │
│ Navigation      │    │ (Horizontal     │    │ for Navigation  │
│ Menu            │    │  Scroll)        │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

---

**Legend:**
- `┌─────┐` = Process/Component
- `───▶` = Flow Direction
- `│` = Decision Point
- `▼` = Next Step
