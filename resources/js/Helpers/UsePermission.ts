let permissions: { [key: string]: number } = {}

export default {
    fillPermissions(per: { [key: string]: number}) {
      permissions = per;
    },
    can: (permission: string) => permissions.hasOwnProperty(permission),

    cant: (permission: string) => !permissions.hasOwnProperty(permission),
};
