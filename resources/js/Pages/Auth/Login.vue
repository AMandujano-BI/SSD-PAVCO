<template>
  <Head title="Log in" />

  <!-- NAVBAR -->
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
          Log In
        </h1>
        <jet-validation-errors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
          {{ status }}
        </div>

        <form @submit.prevent="submit">
          <div>
            <div class="relative">
              <div
                class="
                  absolute
                  left-0
                  top-[2px]
                  py-[17px]
                  px-4
                  border-r-[1px] border-[#e1e1e1]
                  bg-[#8fafc]
                  flex
                  items-center
                  justify-center
                "
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink"
                  aria-hidden="true"
                  role="img"
                  width="20"
                  height="20"
                  preserveAspectRatio="xMidYMid meet"
                  viewBox="0 0 32 32"
                >
                  <rect
                    x="0"
                    y="0"
                    width="32"
                    height="32"
                    fill="none"
                    stroke="none"
                  />
                  <path
                    d="M28 6H4a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h24a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2zm-2.2 2L16 14.78L6.2 8zM4 24V8.91l11.43 7.91a1 1 0 0 0 1.14 0L28 8.91V24z"
                    fill="#0271c5"
                  />
                </svg>
              </div>
              <input
                type="text"
                class="pl-16 w-full border-[#e1e1e1] py-4"
                v-model="form.username"
                placeholder="Username"
                required
                autofocus
              />
            </div>
          </div>

          <div class="mt-4">
            <div class="relative">
              <div
                class="
                  absolute
                  left-0
                  top-[2px]
                  py-[17px]
                  px-4
                  border-r-[1px] border-[#e1e1e1]
                  bg-[#8fafc]
                  flex
                  items-center
                  justify-center
                "
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink"
                  aria-hidden="true"
                  role="img"
                  width="20"
                  height="20"
                  preserveAspectRatio="xMidYMid meet"
                  viewBox="0 0 24 24"
                >
                  <rect
                    x="0"
                    y="0"
                    width="24"
                    height="24"
                    fill="none"
                    stroke="none"
                  />
                  <path
                    d="M12 13a1.49 1.49 0 0 0-1 2.61V17a1 1 0 0 0 2 0v-1.39A1.49 1.49 0 0 0 12 13zm5-4V7A5 5 0 0 0 7 7v2a3 3 0 0 0-3 3v7a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-7a3 3 0 0 0-3-3zM9 7a3 3 0 0 1 6 0v2H9zm9 12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1z"
                    fill="#0271c5"
                  />
                </svg>
              </div>
              <input
                type="password"
                class="pl-16 w-full border-[#e1e1e1] py-4"
                v-model="form.password"
                placeholder="Password"
                required
                autofocus
              />
            </div>
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
                Log In
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
          <div class="mt-4 flex justify-between">
            <label class="flex items-center">
              <jet-checkbox
                name="remember"
                v-model:checked="form.remember"
                class="cursor-pointer"
              />
              <span class="ml-2 text-sm text-[#a2a2a2] cursor-pointer"
                >Remember me</span
              >
            </label>
            <Link
              v-if="canResetPassword"
              :href="route('password.request')"
              class="underline text-sm text-[#34689c] hover:text-gray-900"
            >
              Forgot your password?
            </Link>
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
import IconFooterLeftBar from "../../assets/Icons/IconFooterLeftBar/pattern.png";
import NavbarPavcoVue from "../../Jetstream/NavbarPavco.vue";
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
    Navbar: NavbarPavcoVue,
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
