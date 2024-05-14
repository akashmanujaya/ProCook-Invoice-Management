<template>
  <div>
    <div
      class="text-blueGray-500 py-1 px-3"
      href="#pablo"
      ref="btnDropdownRef"
      @mouseenter="toggleDropdown"  
      @mouseleave="closeDropdown"   
    >
      <i class="fa-solid fa-list-ul"></i>
  </div>
    <div
      ref="popoverDropdownRef"
      class="bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48"
      :class="{
        hidden: !dropdownPopoverShow,
        block: dropdownPopoverShow,
      }"
      @mouseenter="keepDropdownOpen"
      @mouseleave="closeDropdown"    
    >
      <a
        href="javascript:void(0);"
        class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700 hover:bg-cyan-50"
        @click="showAlert('Viewing invoice')"
      >
        View Invoice
      </a>
      <a
        href="javascript:void(0);"
        class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700 hover:bg-cyan-50"
        @click="showAlert('Editing invoice')"
      >
        Edit Invoice
      </a>
      <a
        href="javascript:void(0);"
        class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700 hover:bg-cyan-50"
        @click="showAlert('Marking invoice as paid/unpaid')"
      >
        Mark as Paid/Unpaid
      </a>
      <a
        href="javascript:void(0);"
        class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700 hover:bg-cyan-50"
        @click="showAlert('Deleting invoice')"
      >
        Delete Invoice
      </a>
    </div>
  </div>
</template>

<script>
import { createPopper } from "@popperjs/core";

export default {
  data() {
    return {
      dropdownPopoverShow: false,
    };
  },
  methods: {
    toggleDropdown() {
      this.dropdownPopoverShow = true;
      createPopper(this.$refs.btnDropdownRef, this.$refs.popoverDropdownRef, {
        placement: "bottom-start",
      });
    },
    closeDropdown() {
      this.dropdownPopoverShow = false;
    },
    keepDropdownOpen() {
      this.dropdownPopoverShow = true;
    },
    showAlert(message) {
      alert(message);
      this.dropdownPopoverShow = false;  // Close dropdown after action
    },
  },
};
</script>
