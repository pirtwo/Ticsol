<template>
    <nav class="navview-pane">
        <button class="pull-button">
            <span class="default-icon-menu"></span>
        </button>
        <div class="suggest-box">
            <input data-role="search" data-clear-button="false">
            <button class="holder">
                <span class="mif-search"></span>
            </button>
        </div>

        <ul class="navview-menu">
            <li class="active">
                <a href="#">
                    <span class="icon">
                        <span class="mif-dashboard"></span>
                    </span>
                    <span class="caption">Dashboard</span>
                </a>
            </li>

            <li class="item-separator"></li>
            <li class="item-header">Menu</li>

            <li>
                <a href="#">
                    <span class="icon">
                        <span class="mif-event-available"></span>
                    </span>
                    <span class="caption">Work Availabites</span>
                </a>
            </li>
            <li>
                <a href="#" class="dropdown-toggle">
                    <span class="icon">
                        <span class="mif-books"></span>
                    </span>
                    <span class="caption">Bank Details</span>
                </a>
                <ul class="navview-menu" data-role="dropdown">
                    <li>
                        <a href="#">
                            <span class="icon">
                                <span class="mif-balance-scale"></span>
                            </span>
                            <span class="caption">Leave Balances</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon">
                                <span class="mif-balance-scale"></span>
                            </span>
                            <span class="caption">Leave Balances</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <span class="icon">
                        <span class="mif-balance-scale"></span>
                    </span>
                    <span class="caption">Leave Balances</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="icon">
                        <span class="mif-dollars"></span>
                    </span>
                    <span class="caption">Payment Summary</span>
                </a>
            </li>
        </ul>
    </nav>
</template>
<script>
export default {};
</script>
<style scoped>

</style>