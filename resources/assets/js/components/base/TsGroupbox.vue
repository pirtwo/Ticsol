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
      ref="tsGroupbox"
      @click="show = !disabled ? true : false"      
      :class="[{ 'disabled': disabled }, 'ts-groupbox form-control']"
      slot-scope="{selects, options, dropdownStatus, isFocused, isSelected, onKeyDown, toggleOption, deSelectOption, highlightText}"
    >
      <!-- input -->
      <div
        class="ts-groupbox__input"        
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
            :btn-close="!disabled"
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
            :btn-close="!disabled"
            @close="deSelectOption(value)"
          >
            <div>{{ value.value }}</div>
            <img
              v-if="value.icon"
              :src="value.icon"
              width="30"
              height="30"
            >
          </ts-chips>
        </template>
      </div>

      <!-- toggole btn-->
      <button
        type="button"
        :disabled="disabled"
        class="ts-groupbox__toggleBtn btn btn-sm btn-link"
      >
        <i class="material-icons">{{ show ? 'expand_less' : 'expand_more' }}</i>
      </button>

      <!-- dropdown -->
      <div
        tabindex="0"
        v-show="show"
        @keydown="onKeyDown" 
        :class="[{ 'ts-groupbox__dropdown--open' : show, 'ts-groupbox__dropdown--close' : !show }, 'ts-groupbox__dropdown']"        
      >
        <input
          type="text"
          v-model="query"
          :placeholder="searchPlaceholder"
          class="ts-groupbox__search form-control form-control-sm"
        >
        <template v-if="query">
          <ul class="ts-groupbox__list">
            <li
              tabindex="0"
              v-for="opt in options"
              :key="opt.key"
              :class="[{'ts-groupbox__options--selected': isSelected(opt)}, 'ts-groupbox__options']"
              @click="toggleOption(opt)"            
              @keydown="onKeyDown($event, opt)"
            >
              <div class="ts-groupbox__options__content">
                <slot :item="opt" />  
                <div
                  class="ts-groupbox__options__lable"
                  v-html="highlightText(opt.value, '#ff0000')"
                />
              </div>
            </li>
          </ul>
        </template>
        <template v-else>
          <tree
            :roots="parents"
            :data="data"
            class="ts-groupbox__list"          
          >
            <template
              slot-scope="{ item }"
              class="ts-groupbox__options__content"
            >
              <div
                class="ts-groupbox__options__content"
                @click="toggleOption(item)"              
                :class="[{'ts-groupbox__options--selected': isSelected(item) }, 'ts-groupbox__options']"
              >
                <div
                  class="ts-groupbox__options__lable"
                  v-html="highlightText(item.value, '#ff0000')"
                />
              </div>
            </template>
          </tree>    
        </template>            
      </div>
      <!-- END dropdown -->
    </div>
  </base-selectbox>
</template>

<script>
import Chips from "./TsChips";
import Tree from "./TsTree";
import BaseSelectbox from "./core/BaseSelectbox";

export default {
  name: "TsGroupbox",

  components: {
    "tree": Tree,
    "ts-chips": Chips,
    "base-selectbox": BaseSelectbox
  },

  props: {
    value: {
      type: [Object, Array],
      default: () => {
        return [];
      }
    },
    data: {
      type: Array,
      default: () => {
        return [];
      }
    },
    multi: {
      type: Boolean,
      default: false
    },
    placeholder: {
      type: String,
      default: ""
    },
    searchPlaceholder: {
      type: String,
      default: ""
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      query: "",
      show: false,
    };
  },  

  computed:{
    parents:function(){
      return this.data.filter(item=>item.parentKey === null);
    }
  },

  mounted() {
    document.addEventListener("click", this.onClose);
  },

  destroyed() {
    document.removeEventListener("click", this.onClose);
  },

  methods: {
    onClose(e) {
      let elm = this.$refs.tsGroupbox;
      let target = e.target;
      if (elm !== target && !elm.contains(target)) this.show = false;
    }
  }
};
</script>

<style scoped>
.ts-groupbox {
  position: relative;  
  height: auto !important;
}

.disabled {
  background-color: #e9ecef !important;
}

.ts-groupbox__input {
  width: 100%;
  padding: 0px;
  padding-right: 100px;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  max-height: 100px;
  overflow-x: hidden;
  overflow-y: auto;
  cursor: default;
}

.ts-groupbox__toggleBtn {
  top: 0px;
  right: 0px;
  height: 100%;
  position: absolute;
}

.ts-groupbox__toggleBtn i {
  line-height: 100%;
}

.is-invalid .ts-groupbox__toggleBtn {  
  right: 20px;  
}

.ts-groupbox__dropdown {
  top: 100%;
  left: 0px;
  width: 100%;
  padding: 15px;
  z-index: 100;
  margin-top: 2px;
  position: absolute;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-top: 0px;
  background-color: white;
  transition: all 0.3s;
}

.ts-groupbox__dropdown--open {
  animation-duration: 0.3s;
  animation-name: dropdown-open;
}

.ts-groupbox__dropdown--close {
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

.ts-groupbox__search {
  width: 100%;
  margin-top: 7px;
  margin-bottom: 7px;
}

.ts-groupbox__list {
  margin: 0px;
  padding: 0px;
  max-height: 200px;
  overflow-x: hidden;
  overflow-y: auto;
  list-style: none;
}

.ts-groupbox__options {
  padding: 5px;
  cursor: pointer;
}

.ts-groupbox__options--selected {
  background-color: #ffeb3b;
}

.ts-groupbox__options--focused,
.ts-groupbox__options:hover {
  background-color: #f5f5f5;
}

.ts-groupbox__options__lable {
  padding-left: 10px;
}

.ts-groupbox__options__content {
  display: flex;
  align-items: center;
  flex-direction: row;
}
</style>
