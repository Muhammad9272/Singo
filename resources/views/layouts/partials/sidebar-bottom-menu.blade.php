<x-layout.sidebar.nav-item
    name="Settings"
    icon="fas fa-user-cog"
    route="user.setting"
/>

@if(auth()->user()->type != 0)
    <x-layout.sidebar.nav-item
        name="Support"
        icon="fas fa-question-circle"
        route="support"
    />
@endif
