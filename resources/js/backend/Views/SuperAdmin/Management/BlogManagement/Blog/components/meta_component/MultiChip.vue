<template>
  <div class="col-md-6">
    <div class="form-group">
      <label for="">Enter your tag</label>
      <div class="mt-1 mb-3">
        <div class="bootstrap-tagsinput" style="min-height: 40px">
          <template v-for="item in set_blog_tags" :key="item">
            <span class="tag badge badge-light">{{ item }}<span data-role="remove" @click="removeTag(item)"></span></span>
          </template>
          <input type="text" placeholder="" v-on:keydown.enter="onEnter" v-model="tag_input_value" />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapActions, mapState, mapWritableState } from "pinia";
import { store } from "../../store";

export default {
  props: {
    name: {
      type: String,
    },
    value: {
      type: Array,
      default: [],
    },
  },
  data: () => ({
    set_blog_tags: [],
    tag_input_value: "",
    tags: "tag one ,tag two,tag three,",
  }),
  created: function () {
    if (this.item) {
      let tagData = this.tags.split(",");
      tagData.pop();
      tagData.forEach((item) => {
        this.set_tags(item);
      });
    }
  },

  methods: {
    ...mapActions(store, ["get_all", "set_paginate", "set_page"]),
    set_tags: async function (item) {
      if (item == "empty") {
        this.set_blog_tags = [];
        return false;
      }

      let is_exist = this.set_blog_tags.some((data) => data === item);
      if (!is_exist) {
        this.set_blog_tags.push(item);
      }
    },
    remove_tag: async function (item) {
      this.set_blog_tags = this.set_blog_tags.filter((data) => data != item);
    },
    onEnter: function () {
      event.preventDefault();
      this.set_tags(this.tag_input_value);
      this.tag_input_value = "";
    },
    removeTag: function (item) {
      this.remove_tag(item);
    },
  },
  computed: {
    ...mapState(store, ["item"]),
  },
};
</script>
<style lang=""></style>
