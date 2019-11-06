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
      ref="tsChipsbox"
      @click="show = !disabled ? true : false"      
      :class="[{ 'disabled': disabled }, 'ts-chipsbox form-control']"
      slot-scope="{selects, options, dropdownStatus, isFocused, isSelected, onKeyDown, toggleOption, deSelectOption, highlightText}"
    >
      <!-- input -->
      <div class="ts-chipsbox__input">
        <span
          class="text-muted"
          v-if="selects === null || selects.length === 0"
        >{{ placeholder }}</span>
        <template v-if="multi">
          <ts-chips
            v-for="item in value"
            :key="item.key"
            :btn-close="!disabled"
            :title="item.value"
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
            :title="value.value"
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
        :disabled="disabled"
        type="button"
        class="ts-chipsbox__toggleBtn btn btn-sm btn-link"
      >
        <i class="material-icons">{{ show ? 'expand_less' : 'expand_more' }}</i>
      </button>

      <!-- dropdown -->
      <div
        tabindex="0"
        v-show="show"
        @keydown="onKeyDown"
        :class="[{ 'ts-chipsbox__dropdown--open' : show, 'ts-chipsbox__dropdown--close' : !show }, 'ts-chipsbox__dropdown']"
      >
        <input
          type="text"
          v-model="query"
          :placeholder="searchPlaceholder"
          class="ts-chipsbox__search form-control form-control-sm"
        >
        <ul
          class="ts-chipsbox__list"
          @keydown="onKeyDown"
        >
          <li
            tabindex="0"
            v-for="opt in options"
            :key="opt.key"
            :class="[{'ts-chipsbox__options--selected': isSelected(opt)}, 'ts-chipsbox__options']"
            @click="toggleOption(opt)"
            @keydown="onKeyDown($event, opt)"
          >
            <div class="ts-chipsbox__options__content">
              <slot :item="opt" />
              <div
                class="ts-chipsbox__options__lable"
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
      show: false
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
      let elm = this.$refs.tsChipsbox;
      let target = e.target;
      if (elm !== target && !elm.contains(target)) this.show = false;
    }
  }
};
</script>

<style scoped>
.ts-chipsbox {
  position: relative;
  height: auto !important;
}

.disabled {
  background-color: #e9ecef !important;
}

.ts-chipsbox__input {
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

.ts-chipsbox__toggleBtn {
  top: 0px;
  right: 0px;
  height: 100%;
  position: absolute;
}

.ts-chipsbox__toggleBtn i {
  line-height: 100%;
}

.is-invalid .ts-chipsbox__toggleBtn {
  right: 20px;
}

.ts-chipsbox__dropdown {
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

.ts-chipsbox__dropdown--open {
  animation-duration: 0.3s;
  animation-name: dropdown-open;
}

.ts-chipsbox__dropdown--close {
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

.ts-chipsbox__search {
  width: 100%;
  margin-top: 7px;
  margin-bottom: 7px;
}

.ts-chipsbox__list {
  margin: 0px;
  padding: 0px;
  max-height: 200px;
  overflow-x: hidden;
  overflow-y: auto;
  list-style: none;
}

.ts-chipsbox__options {
  padding: 5px;
  cursor: pointer;
}

.ts-chipsbox__options--selected {
  background-color: #ffeb3b;
}

.ts-chipsbox__options--focused,
.ts-chipsbox__options:hover {
  background-color: #f5f5f5;
}

.ts-chipsbox__options__lable {
  padding-left: 10px;
}

.ts-chipsbox__options__content {
  display: flex;
  align-items: center;
  flex-direction: row;
}
</style>
