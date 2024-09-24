<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            {{ trans('panel.site_title') }}
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        @can('user_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/permissions*") ? "c-show" : "" }} {{ request()->is("admin/roles*") ? "c-show" : "" }} {{ request()->is("admin/users*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.members.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                Members
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('records_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/records*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-book c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.recordsManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('record_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.records.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/records") || request()->is("admin/records/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-file-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.record.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('news_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/newss*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw far fa-newspaper c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.newsManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('news_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.newss.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/newss") || request()->is("admin/newss/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-newspaper c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.news.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('news_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.comments.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/newss") || request()->is("admin/newss/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-newspaper c-sidebar-nav-icon">

                                </i>
                                Comments
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('family_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/families*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    Community Management
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('news_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.families.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/families") || request()->is("admin/families/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-users-cog c-sidebar-nav-icon">

                                </i>
                                Communities
                            </a>
                        </li>
                    @endcan
                    @can('news_access')
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.reportedFamilies.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/reportedFamilies") || request()->is("admin/reportedFamilies/*") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-users-cog c-sidebar-nav-icon">

                            </i>
                            Reported Communities
                        </a>
                    </li>
                @endcan
                </ul>
            </li>
        @endcan
        @can('dynasty_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/lineages*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    Conversation Management
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('news_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.dynasties.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/dynasties") || request()->is("admin/dynasties/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-users-cog c-sidebar-nav-icon">

                                </i>
                                Conversations
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('resources_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/content-types*") ? "c-show" : "" }} {{ request()->is("admin/content-categories*") ? "c-show" : "" }} {{ request()->is("admin/contents*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fab fa-blogger-b c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.resourcesManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('content_type_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.content-types.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/content-types") || request()->is("admin/content-types/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-list c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.contentType.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('content_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.content-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/content-categories") || request()->is("admin/content-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-list-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.contentCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('content_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.contents.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/contents") || request()->is("admin/contents/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-blogger c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.content.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('post_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/reportedPosts*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    Post Management
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('news_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.reportedPosts.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/reportedPosts") || request()->is("admin/reportedPosts/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-users-cog c-sidebar-nav-icon">

                                </i>
                                Reported Posts
                            </a>
                        </li>
                    @endcan
                    @can('news_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.promotePosts.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/promotePosts") || request()->is("admin/promotePosts/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-users-cog c-sidebar-nav-icon">

                                </i>
                                Promote Posts
                            </a>
                        </li>
                    @endcan
                    @can('news_access')
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.inactivePosts.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/inactivePosts") || request()->is("admin/inactivePosts/*") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-users-cog c-sidebar-nav-icon">

                            </i>
                            Inactive Posts
                        </a>
                    </li>
                @endcan
                </ul>
            </li>
        @endcan
        @can('restaurants_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/restaurants*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-utensils c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.restaurantsManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('resource_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.restaurant-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/restaurant-categories") || request()->is("admin/restaurant-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-utensils c-sidebar-nav-icon">

                                </i>
                                Restaurant Category
                            </a>
                        </li>
                    @endcan
                    @can('restaurant_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.restaurants.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/restaurants") || request()->is("admin/restaurants/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-utensils c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.restaurant.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('advert_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/adverts*") ? "c-show" : "" }} {{ request()->is("admin/advert-categories*") ? "c-show" : "" }} {{ request()->is("admin/advert-inquiries*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fab fa-adversal c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.advertManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('advert_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.adverts.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/adverts") || request()->is("admin/adverts/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-adversal c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.advert.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('advert_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.advert-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/advert-categories") || request()->is("admin/advert-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-align-right c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.advertCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('advert_inquiry_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.advert-inquiries.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/advert-inquiries") || request()->is("admin/advert-inquiries/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.advertInquiry.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('interests_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/interests*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-utensils c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.interestsManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('restaurant_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.interests.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/interests") || request()->is("admin/interest/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-utensils c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.interest.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('event_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/events*") ? "c-show" : "" }} {{ request()->is("admin/event-categories*") ? "c-show" : "" }} {{ request()->is("admin/event-orders*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw far fa-calendar-alt c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.eventManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('event_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.events.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/events") || request()->is("admin/events/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-calendar-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.event.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('event_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.event-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/event-categories") || request()->is("admin/event-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-calendar-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.eventCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('event_order_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.event-orders.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/event-orders") || request()->is("admin/event-orders/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.eventOrder.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('event_order_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.eventAttendees.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/event-attendees") || request()->is("admin/event-attendees/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                Event Attendees
                            </a>
                        </li>
                    @endcan
                    @can('event_order_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.ticketType.create") }}" class="c-sidebar-nav-link {{ request()->is("admin/event-attendees") || request()->is("admin/event-attendees/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                Event Ticket Type
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('resources_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/resource-categories*") ? "c-show" : "" }} {{ request()->is("admin/resources*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw far fa-newspaper c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.resourcesManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('resource_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.resource-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/resource-categories") || request()->is("admin/resource-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.resourceCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('resource_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.resources.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/resources") || request()->is("admin/resources/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.resource.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        {{-- settings management --}}
        @can('resources_management_access')
        <li class="c-sidebar-nav-dropdown {{ request()->is("admin/settings*") ? "c-show" : "" }} {{ request()->is("admin/settings*") ? "c-show" : "" }}">
            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                <i class="fa-fw far fa-newspaper c-sidebar-nav-icon">

                </i>
                Settings Management
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                @can('resource_category_access')
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.settings.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/settings") || request()->is("admin/settings/*") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                            </i>
                            Settings
                        </a>
                    </li>
                @endcan
            </ul>
        </li>
        @endcan
        {{-- learning and coaching management --}}
        @can('resources_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/learning-categories*") ? "c-show" : "" }} {{ request()->is("admin/learning*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw far fa-newspaper c-sidebar-nav-icon">

                    </i>
                    Learning & Coaching Mgt
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('resource_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.learning-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/learning-categories") || request()->is("admin/learning-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                Learning Category
                            </a>
                        </li>
                    @endcan
                    @can('resource_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.coaches.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/coacges") || request()->is("admin/coaches/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                Coach
                            </a>
                        </li>
                    @endcan
                    @can('resource_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.coaching-videos.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/coaching-videos") || request()->is("admin/coaching-videos/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                Coaching Videos
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        {{--  @can('travels_and_spirituality_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/travels*") ? "c-show" : "" }} {{ request()->is("admin/travel-orders*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-plane-departure c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.travelsAndSpirituality.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('travel_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.travels.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/travels") || request()->is("admin/travels/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-bus c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.travel.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('travel_order_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.travel-orders.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/travel-orders") || request()->is("admin/travel-orders/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-fighter-jet c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.travelOrder.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan  --}}
        @can('store_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/products*") ? "c-show" : "" }} {{ request()->is("admin/product-categories*") ? "c-show" : "" }} {{ request()->is("admin/product-sub-categories*") ? "c-show" : "" }} {{ request()->is("admin/product-variations*") ? "c-show" : "" }} {{ request()->is("admin/product-ratings*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-store c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.storeManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('product_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.products.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/products") || request()->is("admin/products/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-shopping-cart c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.product.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('product_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.product-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/product-categories") || request()->is("admin/product-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-product-hunt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.productCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('product_sub_category_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.product-sub-categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/product-sub-categories") || request()->is("admin/product-sub-categories/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-shirtsinbulk c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.productSubCategory.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('product_variation_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.product-variations.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/product-variations") || request()->is("admin/product-variations/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.productVariation.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('product_rating_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.product-ratings.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/product-ratings") || request()->is("admin/product-ratings/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.productRating.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('product_rating_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.colors.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/colors") || request()->is("admin/colors/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.color.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('product_rating_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.product.order") }}" class="c-sidebar-nav-link {{ request()->is("admin/product-orders") || request()->is("admin/product-orders/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                                </i>
                                Orders
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        {{--  @can('explore_cultural_learning_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/courses*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.exploreCulturalLearning.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('course_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.courses.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/courses") || request()->is("admin/courses/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-book c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.course.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan  --}}
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                        <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                        </i>
                        {{ trans('global.change_password') }}
                    </a>
                </li>
            @endcan
        @endif
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>
