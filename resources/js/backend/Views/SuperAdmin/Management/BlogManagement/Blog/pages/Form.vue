<template>
  <div>
    <form @submit.prevent="submitHandler">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h5 class="text-capitalize">
            {{ param_id ? `${setup.edit_page_title}` : `${setup.create_page_title}` }}
          </h5>
          <div>
            <router-link
              v-if="item.slug"
              class="btn btn-outline-info mr-2 btn-sm"
              :to="{
                name: `Details${setup.route_prefix}`,
                params: { id: item.slug },
              }"
            >
              {{ setup.details_page_title }}
            </router-link>
            <router-link class="btn btn-outline-warning btn-sm" :to="{ name: `All${setup.route_prefix}` }">
              {{ setup.all_page_title }}
            </router-link>
          </div>
        </div>
        <div class="card-body card_body_fixed_height">
          <div class="row">
            <template v-for="(form_field, index) in form_fields" v-bind:key="index">
              <common-input
                :label="form_field.label"
                :type="form_field.type"
                :name="form_field.name"
                :multiple="form_field.multiple"
                :value="form_field.value"
                :data_list="form_field.data_list"
                :is_visible="form_field.is_visible"
                :row_col_class="form_field.row_col_class"
              />
            </template>
            <multi-chip :name="`tags`" />
            <blog-category-drop-down-el :name="'categories'" :multiple="true" />
            <blog-writer-drop-down-el :name="'writers'" :multiple="false" />
            <multiple-image-uploader :name="form_field.name" :accept="form_field.accept" :images="form_field.images_list" />
            <div class="col-md-12">
              <hr />
              <div class="d-flex justify-content-between align-items-center pb-2 section-title">
                <h5 class="m-0">Add Contributors</h5>
                <button class="btn btn-sm btn-outline-success" @click.prevent="add_row('contributor')">Add row</button>
              </div>
              <hr />
              <div class="row align-items-center" v-for="(contributor, index) in contributor_data" :key="index">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="">name</label>
                    <div class="mt-1 mb-3">
                      <input
                        class="form-control form-control-square mb-2"
                        type="text"
                        :name="`contributor[${index}][name]`"
                        v-model="contributor.name"
                        id="name"
                        :class="{
                          custom_error: errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].name,
                        }"
                      />
                    </div>
                    <div v-if="errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].name" class="text-danger small">
                      {{ errors["contributor"][index].name }}
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="">Age</label>
                    <div class="mt-1 mb-3">
                      <select
                        class="form-control form-control-square mb-2"
                        :name="`contributor[${index}][age]`"
                        v-model="contributor.age"
                        :class="{
                          custom_error: errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].age,
                        }"
                      >
                        <option value="">-- select --</option>
                        <option v-for="(age, i) in 50" :key="i" :value="age">
                          {{ age }}
                        </option>
                      </select>
                    </div>
                    <div v-if="errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].age" class="text-danger small">
                      {{ errors["contributor"][index].age }}
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="">email</label>
                    <div class="mt-1 mb-3">
                      <input
                        class="form-control form-control-square mb-2"
                        type="text"
                        :name="`contributor[${index}][email]`"
                        v-model="contributor.email"
                        id="email"
                        :class="{
                          custom_error: errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].email,
                        }"
                      />
                    </div>
                    <div v-if="errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].email" class="text-danger small">
                      {{ errors["contributor"][index].email }}
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div>
                      <label for="">image</label>
                      <a :href="contributor_data[index].image" data-lightbox="image-1" data-title="My caption">
                        <img class="image_preview" v-if="contributor_data[index].image" :src="contributor_data[index].image" />
                      </a>
                    </div>

                    <div class="mt-1 mb-3">
                      <input
                        class="form-control form-control-square mb-2"
                        type="file"
                        @change="onImageChange($event, index)"
                        :name="`contributor[${index}][image]`"
                        id="image"
                        :class="{
                          custom_error: errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].image,
                        }"
                      />
                    </div>
                    <div v-if="errors['contributor'] && errors['contributor'][index] && errors['contributor'][index].image" class="text-danger small">
                      {{ errors["contributor"][index].image }}
                    </div>
                  </div>
                </div>
                <div class="col-md-1 d-flex align-items-center justify-content-center">
                  <button
                    class="btn btn-sm btn-outline-danger"
                    :style="{
                      width: '50%',
                      marginTop: !errors['contributor']?.[index]?.title ? '30px' : '0',
                    }"
                    @click.prevent="delete_row('contributor', index)"
                  >
                    <i class="fa fa-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-light btn-square px-5">
            <i class="icon-lock"></i>
            Submit
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { store } from "../store";
import setup from "../setup";
import form_fields from "../setup/form_fields";
import MultiChip from "../components/meta_component/MultiChip.vue";
import BlogCategoryDropDownEl from "../../BlogCategory/components/dropdown/DropDownEl.vue";
import BlogWriterDropDownEl from "../../BlogWriter/components/dropdown/DropDownEl.vue";
import MultipleImageUploader from "../components/meta_component/MultipleImageUploader.vue";

export default {
  components: {
    MultiChip,
    BlogCategoryDropDownEl,
    BlogWriterDropDownEl,
    MultipleImageUploader,
  },
  data: () => ({
    setup,
    form_fields,
    param_id: null,
    form_field: {
      name: "images",
      accept: "image/*",
      images_list: [],
    },
    errors: [],
    //----------- for floor_plan list input ----------

    contributor_data: [
      {
        name: "",
        email: "",
        image: "",
      },
    ],

    //----------- for floor_plan list input ----------
  }),
  created: async function () {
    let id = (this.param_id = this.$route.params.id);
    this.reset_fields();
    if (id) {
      this.set_fields(id);
    }
  },
  methods: {
    ...mapActions(store, {
      create: "create",
      update: "update",
      details: "details",
      get_all: "get_all",
      set_only_latest_data: "set_only_latest_data",
    }),
    reset_fields: function () {
      this.form_fields.forEach((item) => {
        item.value = "";
      });
    },
    set_fields: async function (id) {
      this.param_id = id;
      await this.details(id);
      if (this.item) {
        this.form_fields.forEach((field, index) => {
          Object.entries(this.item).forEach((value) => {
            if (field.name == value[0]) {
              this.form_fields[index].value = value[1];
            }
          });
        });
      }
    },

    submitHandler: async function ($event) {
      this.set_only_latest_data(true);
      if (this.param_id) {
        let response = await this.update($event);
        // await this.get_all();
        if ([200, 201].includes(response.status)) {
          window.s_alert("Data successfully updated");
          this.$router.push({ name: `Details${this.setup.route_prefix}` });
        }
      } else {
        let response = await this.create($event);
        // await this.get_all();
        if ([200, 201].includes(response.status)) {
          window.s_alert("Data Successfully Created");
          this.$router.push({ name: `All${this.setup.route_prefix}` });
        }
      }
    },
    add_row() {
      this.contributor_data.push({
        name: "",
        email: "",
        image: "",
      });
    },
  },

  computed: {
    ...mapState(store, {
      item: "item",
    }),
  },
};
</script>
