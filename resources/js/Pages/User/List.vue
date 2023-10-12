<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { UserPagination, User } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import permission from '@/Helpers/UsePermission';
import { ref, onMounted } from '@vue/runtime-core';
import { initModals } from 'flowbite';

permission.fillPermissions(usePage().props.auth.permissions);

const users = ref(usePage().props.users as UserPagination);
const showUser = ref<User>({
  id: 0,
  name: '',
  email: '',
  telephone: '',
  position: '',
  email_verified_at: '',
  last_signed_in_at: '',
  updated_at: '',
  created_at: '',
});
const changePassword = ref({
  current_password: '',
  new_password: '',
});


let timeoutId = 0;

/**
 * 查詢使用者並使用 debounce 避免過多的 api 請求
 * @param {Event} event
 */
function searchUser(event: Event) {
  let search = (event.target as HTMLInputElement).value;

  // 重置 timeout
  clearTimeout(timeoutId);

  // 1 秒內沒有輸入，就查詢使用者
  timeoutId = setTimeout(() => {
    // api 查詢使用者
    router.get(
      route('user.list'),
      { search },
      {
        onSuccess: ({ props }) => {
          users.value = props.users as UserPagination;
        }
      }
    );
  }, 1000);
}

function updateUser(e: Event) {
  e.preventDefault();

  router.patch(
    route('user.update', showUser.value.id),
    {
      name: showUser.value.name,
      position: showUser.value.position,
      email: showUser.value.email,
      telephone: showUser.value.telephone,
      current_password: changePassword.value.current_password,
      new_password: changePassword.value.new_password,
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        alert('更新成功')
        window.location.reload();
      }
    }
  );
}
onMounted(() => {
  initModals()
})

</script>
<template>
  <AuthenticatedLayout>
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-5 bg-white dark:bg-gray-900 antialiased">
          <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex items-center justify-between py-4 bg-white dark:bg-gray-800">
              <div>
                <button id="dropdownActionButton" data-dropdown-toggle="dropdownAction"
                  class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                  type="button">
                  <span class="sr-only">Action button</span>
                  Action
                  <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m1 1 4 4 4-4" />
                  </svg>
                </button>
                <!-- Dropdown menu -->
                <div id="dropdownAction"
                  class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                  <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownActionButton">
                    <li>
                      <a href="#"
                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Reward</a>
                    </li>
                    <li>
                      <a href="#"
                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Promote</a>
                    </li>
                    <li>
                      <a href="#"
                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Activate
                        account</a>
                    </li>
                  </ul>
                  <div class="py-1">
                    <a href="#"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Delete
                      User</a>
                  </div>
                </div>
              </div>
              <label for="table-search" class="sr-only">Search</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                  </svg>
                </div>
                <input @input="searchUser" type="text" id="table-search-users"
                  class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="Search for users">
              </div>
            </div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                  <th scope="col" class="p-4">
                    <div class="flex items-center">
                      <input id="checkbox-all-search" type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                      <label for="checkbox-all-search" class="sr-only">checkbox</label>
                    </div>
                  </th>
                  <th scope="col" class="px-6 py-3">
                    Name
                  </th>
                  <th scope="col" class="px-6 py-3">
                    Position
                  </th>
                  <th scope="col" class="px-6 py-3">
                    Status
                  </th>
                  <th scope="col" class="px-6 py-3">
                    Action
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in users.data"
                  class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                  <td class="w-4 p-4">
                    <div class="flex items-center">
                      <input id="checkbox-table-search-1" type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                      <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                    </div>
                  </td>
                  <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                    <img class="w-10 h-10 rounded-full" :src="`https://ui-avatars.com/api/?name=${user.name}`"
                      :alt="user.name">
                    <div class="pl-3">
                      <div class="text-base font-semibold" v-text="user.name"></div>
                      <div class="font-normal text-gray-500" v-text="user.email"></div>
                    </div>
                  </th>
                  <td class="px-6 py-4" v-text="user.position">
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div :class="{ 'bg-red-500': Math.random() > 0.5 }"
                        class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></div> Online
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <!-- Modal toggle -->
                    <a href="#" v-if="permission.can('user.view')" @click="showUser = user" type="button"
                      data-modal-target="editUserModal" data-modal-show="editUserModal"
                      class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Detail</a>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- Edit user modal -->
            <div id="editUserModal" tabindex="-1" aria-hidden="true"
              class="fixed top-0 left-0 right-0 z-50 items-center justify-center hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
              <div class="relative w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <form class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                  <!-- Modal header -->
                  <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                      User Detail
                    </h3>
                    <button type="button"
                      class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                      data-modal-hide="editUserModal">
                      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                      </svg>
                      <span class="sr-only">Close modal</span>
                    </button>
                  </div>
                  <!-- Modal body -->
                  <div class="p-6 space-y-6">
                    <div class="grid grid-cols-6 gap-6">
                      <div class="col-span-6 sm:col-span-3">
                        <label for="name"
                          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="text" name="name" id="name"
                          class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="Bonnie" v-model="showUser.name" required>
                          <div class="mt-1 text-xs text-red-800" v-show="$page.props.errors.name" v-text="$page.props.errors.name">
                          </div>
                      </div>
                      <div class="col-span-6 sm:col-span-3">
                        <label for="position"
                          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Position</label>
                        <input type="text" name="position" id="position"
                          class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="Engineer" v-model="showUser.position">
                          <div class="mt-1 text-xs text-red-800" v-show="$page.props.errors.position" v-text="$page.props.errors.position">
                          </div>
                      </div>
                      <div class="col-span-6 sm:col-span-3">
                        <label for="email"
                          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" name="email" id="email"
                          class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 disabled:text-gray-400 dark:disabled:text-gray-500"
                          placeholder="example@company.com" readonly disabled :value="showUser.email" required>
                      </div>
                      <div class="col-span-6 sm:col-span-3">
                        <label for="phone-number"
                          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone
                          Number</label>
                        <input type="text" name="phone-number" id="phone-number"
                          class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="e.g. +123456789" v-model="showUser.telephone">
                          <div class="mt-1 text-xs text-red-800" v-show="$page.props.errors.telephone" v-text="$page.props.errors.telephone">
                          </div>
                      </div>
                    </div>
                  </div>
                  <!-- Modal footer -->
                  <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" v-if="permission.can('user.update')" @click="updateUser"
                      class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update
                      User</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
