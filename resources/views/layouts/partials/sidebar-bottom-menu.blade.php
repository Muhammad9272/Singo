<x-layout.sidebar.nav-item
    uname="suser"   
    name="Settings"
    icon="{{ asset('image/icons/setting.svg') }}"
    route="user.setting"
/>

@if(auth()->user()->type != 0)
    <x-layout.sidebar.nav-item
        name="Support"
        icon="fas fa-question-circle"
        route="support"
    />
@endif
