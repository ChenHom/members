export interface User {
  id: number;
  name: string;
  email: string;
  telephone: string;
  position: string;
  email_verified_at: string;
  last_signed_in_at: string;
  created_at: string;
  updated_at: string;
}

export interface PaginationMeta {
  total: number;
  count: number;
  per_page: number;
  current_page: number;
  total_pages: number;
  links: {
    previous: string | null;
    next: string | null;
  };
}

export type UserPagination = { data: User[]; } & PaginationMeta;

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: User;
    permissions: { [key: string]: number };
  };
  flash: {
    success?: string;
    message?: string;
    error?: string;
  };
};
