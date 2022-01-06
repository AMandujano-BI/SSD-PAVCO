<template>
  <Head title="Log in" />
  <div class="h-[120px] flex items-center justify-center">
    <div
      class="
        bg-[#0271c5]
        h-full
        flex
        items-center
        justify-center
        w-full
        flex-1
        md:w-[235px] md:flex-none
      "
    >
      <pavco-icon />
    </div>
    <h1
      class="
        text-center text-[#3b4559] text-[20px]
        font-semibold
        flex-1
        hidden
        md:block
      "
    >
      Salt Spray Database
    </h1>
  </div>

<div class="flex  w-full">
  <div class="md:w-[235px]  hidden md:flex flex-col">
    <img :src="IconFooterLeftBar" alt="iconLeftBar" class="w-[235px] h-[239px]  " />
    <img :src="IconFooterLeftBar" alt="iconLeftBar" class="w-[235px] h-[239px]  " />
    <img :src="IconFooterLeftBar" alt="iconLeftBar" class="w-[235px] h-[239px]  " />
  </div>

  <div class="bg-[#f8fafc] flex-1 w-full min-h-[85vh] flex items-center justify-center">
    <div class="max-w-[400px]">
      <jet-validation-errors class="mb-4" />

      <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
      </div>

      <form @submit.prevent="submit">
        <div>
          <jet-label for="username" value="Username" />
          <jet-input
            id="username"
            type="text"
            class="mt-1 block w-full"
            v-model="form.username"
            required
            autofocus
          />
        </div>

        <div class="mt-4">
          <jet-label for="password" value="Password" />
          <jet-input
            id="password"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password"
            required
            autocomplete="current-password"
          />
          <div v-if="!form.processing" class="w-full">
            <button
              class="
                w-full
                bg-[#123e81]
                text-white
                p-5
                mt-5
                text-[16px]
                font-semibold
                hover:bg-[#1D4786]
                h-[60px]
              "
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Log in
            </button>
          </div>
          <div v-if="form.processing" class="w-full">
            <button
              class="
                w-full
                bg-[#123e81]
                text-white
                p-5
                mt-5
                text-[16px]
                font-semibold
                hover:bg-[#1D4786]
                h-[60px]
              "
              disabled
            >
              <div
                className="animate-spin rounded-full h-4 w-4 border-b-2 border-t-2 border-white inline-block"
              ></div>
            </button>
          </div>
        </div>
        <div class="block mt-4">
          <label class="flex items-center">
            <jet-checkbox name="remember" v-model:checked="form.remember" />
            <span class="ml-2 text-sm text-gray-600">Remember me</span>
          </label>
        </div>

        <div class="flex items-center justify-end mt-4">
          <!-- <Link
          v-if="canResetPassword"
          :href="route('password.request')"
          class="underline text-sm text-gray-600 hover:text-gray-900"
        >
          Forgot your password?
        </Link> -->
        </div>
      </form>
    </div>
  </div>


</div>
</template>

<script>
import { defineComponent } from "vue";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import pavcoIconVue from "../../assets/Icons/PavcoIcon/pavcoIcon.vue";
import IconFooterLeftBar from "../../assets/Icons/IconFooterLeftBar/pattern.png";
export default defineComponent({
  components: {
    Head,
    JetAuthenticationCard,
    JetAuthenticationCardLogo,
    JetButton,
    JetInput,
    JetCheckbox,
    JetLabel,
    JetValidationErrors,
    Link,
    pavcoIcon: pavcoIconVue,
  },

  props: {
    canResetPassword: Boolean,
    status: String,
  },

  data() {
    return {
      IconFooterLeftBar,
      form: this.$inertia.form({
        username: "",
        password: "",
        remember: false,
      }),
    };
  },

  methods: {
    submit() {
      this.form
        .transform((data) => ({
          ...data,
          remember: this.form.remember ? "on" : "",
        }))
        .post(this.route("login"), {
          onFinish: () => this.form.reset("password"),
        });
    },
  },
});
</script>
