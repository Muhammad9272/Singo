<!-- need to remove -->

<x-layout.sidebar.nav-item
    name="Home"
    icon="fas fa-home"
    route="home"
/>

<x-layout.sidebar.nav-item
    name="My Analytics"
    icon="fas fa-chart-line"
    route="analytics.show"
/>

<x-layout.sidebar.nav-item
    name="Released albums"
    icon="fas fa-music"
    route="albums"
/>

<x-layout.sidebar.nav-item
    name="Release new music"
    icon="fas fa-plus-circle"
    route="release"
/>

<x-layout.sidebar.nav-item
    name="Wallet"
    icon="fas fa-money-bill"
    route="wallet"
/>

@if(auth()->user()->type != 0 )
    <li class="nav-header">
        <p>ADMIN</p>
    </li>
    <x-layout.sidebar.nav-item
        name="Users"
        icon="fas fa-users"
        route="admin.users"
    />
    <x-layout.sidebar.nav-item
        name="Pending albums"
        icon="fas fa-clock"
        route="admin.pending"
    />
    <x-layout.sidebar.nav-item
        name="Approved albums"
        icon="fas fa-check-circle"
        route="admin.approved"
    />
    <x-layout.sidebar.nav-item
        name="Distributed albums"
        icon="fas fa-check-circle"
        route="admin.distributed"
    />
    <x-layout.sidebar.nav-item
        name="Declined albums"
        icon="fas fa-times-circle"
        route="admin.declined"
    />
    <x-layout.sidebar.nav-item
        name="Need Edit albums"
        icon="fas fa-edit"
        route="admin.need-edit"
    />
    <x-layout.sidebar.nav-item
        name="User Requests"
        icon="fas fa-user-tag"
        route="users.requests"
    />

    @if(auth()->user()->type == 1 || auth()->user()->type == 3 )
        <x-layout.sidebar.nav-item
            name="Genres"
            icon="fas fa-list"
            route="genres"
        />
        <x-layout.sidebar.nav-item
            name="Payments"
            icon="fas fa-file-invoice-dollar"
            route="admin.payments"
        />
        <x-layout.sidebar.nav-item
            name="Payout Requests"
            icon="fas fa-money-bill-wave"
            route="admin.payouts"
        />
        <x-layout.sidebar.nav-item
            name="Add Stores"
            icon="fas fa-store"
            route="admin.stores.index"
        />
        <x-layout.sidebar.nav-item
            name="Subscription Plan"
            icon="fas fa-crown"
            route="subscription"
        />
        <x-layout.sidebar.nav-item
            name="Coupons"
            icon="fas fa-money-check-alt"
            route="coupon"
        />
        <x-layout.sidebar.nav-item
            name="Welcome Alert"
            icon="fas fa-exclamation-circle"
            route="welcome.alert"
        />
        <x-layout.sidebar.nav-item
            name="Imports"
            icon="fas fa-file-upload"
            route="admin.imports.index"
        />
    @endif

    <x-layout.sidebar.nav-item
        name="Mail to Users"
        icon="fas fa-envelope"
        route="mail"
    />
    <x-layout.sidebar.nav-item
        name="Ticket"
        icon="fas fa-ticket-alt"
        route="ticket"
    />
@endif
