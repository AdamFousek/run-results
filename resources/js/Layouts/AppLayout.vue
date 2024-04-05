<script setup>
import { computed, ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';
import NaiveUiTheme from '@/NaiveUiTheme.js'
import { NConfigProvider, NAlert } from 'naive-ui'
import UserRoleEnum from '@/Enums/UserRoleEnum.js'

const showingNavigationDropdown = ref(false);

const alert = computed(() => {
    return usePage().props.flash?.alert;
});
</script>

<template>
    <NConfigProvider :theme-overrides="NaiveUiTheme">
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('welcome')" aria-label="Website logo">
                                    <ApplicationLogo
                                            class="block"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('welcome')" :active="route().current('welcome')">
                                    {{ $t('menu.dashboard') }}
                                </NavLink>
                                <NavLink :href="route('runners.index')" :active="route().current('runners.*')">
                                    {{ $t('menu.runners') }}
                                </NavLink>
                                <NavLink :href="route('races.index')" :active="route().current('races.*')">
                                    {{ $t('menu.races') }}
                                </NavLink>
                            </div>
                        </div>

                        <div v-if="!$page.props.auth?.user" class="hidden sm:flex sm:items-center sm:ml-6">
                            <NavLink :href="route('login')" class="m-4">
                                {{ $t('auth.login') }}
                            </NavLink>

                            <NavLink :href="route('register')" class="m-4">
                                {{ $t('auth.register') }}
                            </NavLink>
                        </div>
                        <div v-else class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                    <span class="inline-flex rounded-md">
                        <button
                                type="button"
                                aria-label="Menu"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                            {{ $page.props.auth?.user.username }}

                            <svg
                                    class="ms-2 -me-0.5 h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                            >
                                <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </span>
                                    </template>

                                    <template #content>
                                        <template v-if="$page.props.auth?.user.role === UserRoleEnum.ADMIN">
                                            <DropdownLink :href="route('admin.runners.index')">
                                                {{ $t('menu.administration') }}
                                            </DropdownLink>
                                            <DropdownLink :href="route('admin.settings.index')">{{
                                                    $t('menu.settings')
                                                }}
                                            </DropdownLink>
                                            <hr>
                                        </template>
                                        <DropdownLink :href="route('profile.edit')">{{
                                                $t('menu.profile')
                                            }}
                                        </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            {{ $t('auth.logout') }}
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                    aria-label="Menu"
                                    @click="showingNavigationDropdown = !showingNavigationDropdown"
                                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                            :class="{
                      hidden: showingNavigationDropdown,
                      'inline-flex': !showingNavigationDropdown,
                    }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                            :class="{
                      hidden: !showingNavigationDropdown,
                      'inline-flex': showingNavigationDropdown,
                    }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                        :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                        class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('welcome')" :active="route().current('welcome')">
                            {{ $t('menu.dashboard') }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('runners.index')" :active="route().current('runners.*')">
                            {{ $t('menu.runners') }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('races.index')" :active="route().current('races.*')">
                            {{ $t('menu.races') }}
                        </ResponsiveNavLink>
                    </div>

                    <div v-if="!$page.props.auth?.user" class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('login')" :active="route().current('login')">
                            {{ $t('auth.login') }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('register')" :active="route().current('register')">
                            {{ $t('auth.register') }}
                        </ResponsiveNavLink>
                    </div>
                    <!-- Responsive Settings Options -->
                    <div v-else class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth?.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth?.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <template v-if="$page.props.auth?.user.role === UserRoleEnum.ADMIN">
                                <ResponsiveNavLink :href="route('admin.runners.index')">{{
                                        $t('menu.administration')
                                    }}
                                </ResponsiveNavLink>
                                <hr>
                            </template>
                            <ResponsiveNavLink :href="route('profile.edit')" :active="route().current('profile.*')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                {{ $t('auth.logout') }}
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header"/>
                </div>
            </header>

            <div v-if="alert" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <n-alert class="mt-4" :title="alert.header" :type="alert.type" closable>
                    {{ alert.message }}
                </n-alert>
            </div>

            <!-- Page Content -->
            <main>
                <slot/>
            </main>
        </div>
    </NConfigProvider>
</template>
