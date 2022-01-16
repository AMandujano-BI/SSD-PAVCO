<template>
  <Head title="Reset Password" />
  <navbar />
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
          New Password
        </h1>
        <jet-validation-errors class="mb-4" />

        <form @submit.prevent="submit">
          <div>
            <jet-label for="email" value="Email" />
            <p class="mt-1 block w-full text-[#3b4559]">
              {{ form.email }}
            </p>
          </div>

          <div class="mt-4">
            <jet-label for="password" value="Password" />
            <jet-input
              id="password"
              type="password"
              class="mt-1 block w-full"
              v-model="form.password"
              required
              autocomplete="new-password"
            />
          </div>

          <div class="mt-4">
            <jet-label for="password_confirmation" value="Confirm Password" />
            <jet-input
              id="password_confirmation"
              type="password"
              class="mt-1 block w-full"
              v-model="form.password_confirmation"
              required
              autocomplete="new-password"
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
                Reset Password
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
    Navbar: NavbarPavcoVue,
  },

  props: {
    email: String,
    token: String,
  },

  data() {
    return {
      form: this.$inertia.form({
        token: this.token,
        email: this.email,
        password: "",
        password_confirmation: "",
      }),
    };
  },

  methods: {
    submit() {
      this.form.post(this.route("password.update"), {
        onFinish: () => this.form.reset("password", "password_confirmation"),
      });
    },
  },
});
</script>
