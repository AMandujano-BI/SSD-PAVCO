<template>
  <Head title="Forgot Password" />
  <navbar/>
<div class="flex w-full">
    <div class="md:w-[235px] hidden md:flex flex-col img-leftbar"></div>
       <div
      class="
        bg-[#f8fafc]
        flex-1
        w-full
        min-h-[90vh]
        md:min-h[85vh]
        flex
        justify-center
      "
    >
 <div class="max-w-[450px] p-5 w-full mt-[70px]">
        <h1 class="text-center text-[#434343] text-[30px] pb-6 font-semibold">
          Reset Password
        </h1>
    <div class="mb-4 text-sm text-gray-600">
      Forgot your password? No problem. Just let us know your email address and
      we will email you a password reset link that will allow you to choose a
      new one.
    </div>

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
      {{ status }}
    </div>

    <jet-validation-errors class="mb-4" />

    <form @submit.prevent="submit" autocomplete="off">
      <div>
        <jet-label for="email" value="Email" />
        <jet-input
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autofocus
        />
      </div>

      <div class="flex items-center justify-end mt-4">
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
            :disabled="form.processing"
          >
            Email Password Reset Link
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
    </form>
  </div>
  </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard.vue";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import NavbarPavcoVue from "../../Jetstream/NavbarPavco.vue";
export default defineComponent({
  components: {
    Head,
    JetAuthenticationCard,
    JetAuthenticationCardLogo,
    JetButton,
    JetInput,
    JetLabel,
    JetValidationErrors,
     Navbar:NavbarPavcoVue,
  },

  props: {
    status: String,
  },

  data() {
    return {
      form: this.$inertia.form({
        email: "",
      }),
    };
  },

  methods: {
    submit() {
      this.form.post(this.route("password.email"));
    },
  },
});
</script>
