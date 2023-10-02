// import { usePage } from "@inertiajs/vue3";


// const permissions = usePage().props.auth.permissions.reduce((obj, item) => {
//     obj[item] = true;
//     return obj;
// }, {} as { [key: string]: boolean });
// console.log( usePage().props);

const permissions: { [key: string]: boolean } = {}
export default {
    fillPermissions(per: string[]) {
        per.forEach((item) => {
            permissions[item] = true;
        });
    },
    can: (permission: string) => !!permissions[permission],

    cant: (permission: string) => !permissions[permission],
};
