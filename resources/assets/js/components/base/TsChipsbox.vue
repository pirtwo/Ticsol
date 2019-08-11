<template>
  <base-selectbox
    :value="value"
    :data="data"
    :multi="multi"
    :query="query"
    :flags="'gi'"
    @input="(selects)=>{ $emit('input', selects) }"
  >
    <div      
      tabindex="0"
      @click="show = true"
      ref="ezSelectbox"      
      class="ez-selectbox form-control"      
      slot-scope="{selects, options, dropdownStatus, isFocused, isSelected, onKeyDown, toggleOption, deSelectOption, highlightText}"
    >
      <!-- input -->
      <div  
        class="ez-selectbox__input"
      >
        <span 
          class="text-muted" 
          v-if="selects === null || selects.length === 0"
        >
          {{ placeholder }}
        </span>
        <template v-if="multi">
          <ts-chips            
            v-for="item in value"
            :key="item.key"
            :btn-close="true"
            @close="deSelectOption(item)"
          >
            <div>{{ item.value }}</div>
            <img 
              v-if="item.icon"
              :src="item.icon"
              width="30"
              height="30"
            >
          </ts-chips>
        </template>
        <template v-else>
          <ts-chips
            v-if="value"
            :btn-close="true"
            @close="deSelectOption(value)"
          >
            <div>{{ value.value }}</div>
            <img
              v-if="item.icon"
              :src="value.icon"
              width="30"
              height="30"
            >
          </ts-chips>
        </template>
      </div>

      <!-- dropdown -->
      <div
        tabindex="0"
        v-show="show"
        @keydown="onKeyDown"       
        :class="[{ 'ez-selectbox__dropdown--open' : show, 'ez-selectbox__dropdown--close' : !show }, 'ez-selectbox__dropdown']"          
      >
        <input
          type="text"
          v-model="query"
          :placeholder="searchPlaceholder"
          class="ez-selectbox__search form-control form-control-sm"
        >              
        <ul
          class="ez-selectbox__list"
          @keydown="onKeyDown"
        >
          <li
            tabindex="0"
            v-for="opt in options"
            :key="opt.key"
            :class="[{'ez-selectbox__options--selected': isSelected(opt)}, 'ez-selectbox__options']"
            @click="toggleOption(opt)"            
            @keydown="onKeyDown($event, opt)"
          >
            <div class="ez-selectbox__options__content">
              <slot :item="opt" />  
              <div
                class="ez-selectbox__options__lable"
                v-html="highlightText(opt.value, '#ff0000')"
              />
            </div>
          </li>
        </ul>
      </div>
      <!-- END dropdown -->
    </div>
  </base-selectbox>
</template>

<script>
import Chips from "./TsChips";
import BaseSelectbox from "./core/BaseSelectbox";

export default {
  name: "TsChipsbox",

  components: {
    "ts-chips": Chips,
    "base-selectbox": BaseSelectbox
  },

  props: ["value", "data", "multi", "placeholder", "searchPlaceholder"],

  data() {
    return {
      query: "",
      show: false,
    };
  },  

  mounted() {
    document.addEventListener("click", this.onClose);
  },

  destroyed() {
    document.removeEventListener("click", this.onClose);
  },

  methods: {
    onClose(e) {
      let elm = this.$refs.ezSelectbox;
      let target = e.target;
      if (elm !== target && !elm.contains(target)) this.show = false;
    }
  }
};
</script>

<style scoped>
.ez-selectbox {
  position: relative;
  height: auto !important;
}

.ez-selectbox__input {
  width: 100%;
  padding: 0px;
  display: flex;
  flex-flow: row;
  flex-wrap: wrap;
  max-height: 100px;
  overflow-x: hidden;
  overflow-y: auto;
  cursor: default;
}

.ez-selectbox__dropdown {
  top: 100%;
  left: 0px;
  width: 100%;
  padding: 15px;
  z-index: 100;
  margin-top: 2px;
  border-top: 0px;
  border: 1px solid rgba(0, 0, 0, 0.2);  
  background-color: white;
  transition: all 0.3s;  
  position: absolute;
}

.ez-selectbox__dropdown--open {
  animation-duration: 0.3s;
  animation-name: dropdown-open;
}

.ez-selectbox__dropdown--close {
  animation-duration: 0.3s;
  animation-name: dropdown-close;
}

@keyframes dropdown-open {
  from {
    top: -30px;
    opacity: 0;
  }
}

@keyframes dropdown-close {
  fron {
    top: 0px;
    opacity: 1;
  }
  to {
    top: -30px;
    opacity: 0;
  }
}

.ez-selectbox__search {
  width: 100%;
  margin-top: 7px;
  margin-bottom: 7px;
}

.ez-selectbox__list {
  margin: 0px;
  padding: 0px;
  max-height: 300px;
  overflow-x: hidden;
  overflow-y: auto;
  list-style: none;
}

.ez-selectbox__options {
  padding: 5px;
  cursor: pointer;
}

.ez-selectbox__options--selected {
  background-color: #ffeb3b;
}

.ez-selectbox__options--focused,
.ez-selectbox__options:hover {
  background-color: #f5f5f5;
}

.ez-selectbox__options__lable {
  padding-left: 10px;
}

.ez-selectbox__options__content {
  display: flex;
  align-items: center;
  flex-direction: row;
}
</style>
