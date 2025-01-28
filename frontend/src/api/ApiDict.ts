
interface AuthRequestBodies {
    sendOtp: {
      email: string;
    };
    register: {
      name: string;
      email: string;
      role: 'admin' | 'student' | 'faculty';
      contact_no: string;
      password: string;
      college_id?: string;
    };
    login: {
      email: string;
      password: string;
    };
    updateData: {
      name?: string;
      email?: string;
      contact_no?: string;
    };
    resetPassword: {
      email: string;
      password: string;
      token: string;
    };
  }
  
  interface AdminRequestBodies {
    addCollege: {
      name: string;
      address: string;
      contact_no: string;
    };
    addUser: {
      name: string;
      email: string;
      role: 'admin' | 'principal' | 'faculty';
      contact_no: string;
      password: string;
      college_id: string;
    };
    updateUser: {
      uuid: string;
      name?: string;
      email?: string;
      role?: 'admin' | 'principal' | 'faculty';
      contact_no?: string;
      college_id?: string;
    };
    updateCollege: {
      name?: string;
      address?: string;
      contact_no?: string;
    };
  }
  
  // API Dictionary
  export const apiDictionary = {
    auth: {
      sendOtp: {
        url: '/api/auth/send-otp',
        method: 'POST',
        body: {} as AuthRequestBodies['sendOtp'],
        requiresAuth: false
      },
      register: {
        url: '/api/auth/register',
        method: 'POST',
        body: {} as AuthRequestBodies['register'],
        requiresAuth: false
      },
      login: {
        url: '/api/auth/login',
        method: 'POST',
        body: {} as AuthRequestBodies['login'],
        requiresAuth: false
      },
      logout: {
        url: '/api/auth/logout',
        method: 'POST',
        requiresAuth: true
      },
      getUser: {
        url: '/api/auth/user',
        method: 'GET',
        requiresAuth: true
      },
      forgetPassword: {
        url: '/api/auth/forget-password',
        method: 'POST',
        body: { email: '' },
        requiresAuth: false
      },
      resetPassword: {
        url: '/api/auth/reset-password',
        method: 'POST',
        body: {} as AuthRequestBodies['resetPassword'],
        requiresAuth: false
      },
      updateData: {
        url: '/api/auth/update-data',
        method: 'POST',
        body: {} as AuthRequestBodies['updateData'],
        requiresAuth: true
      }
    },
    admin: {
      addCollege: {
        url: '/api/admin/add-college',
        method: 'POST',
        body: {} as AdminRequestBodies['addCollege'],
        requiresAuth: true
      },
      addUser: {
        url: '/api/admin/add-user',
        method: 'POST',
        body: {} as AdminRequestBodies['addUser'],
        requiresAuth: true
      },
      updateUser: {
        url: '/api/admin/update-user',
        method: 'POST',
        body: {} as AdminRequestBodies['updateUser'],
        requiresAuth: true
      },
      updateCollege: {
        url: '/api/admin/update/college/:id',
        method: 'POST',
        body: {} as AdminRequestBodies['updateCollege'],
        requiresAuth: true
      },
      deleteCollege: {
        url: '/api/admin/delete/college/:id',
        method: 'DELETE',
        requiresAuth: true
      },
      filterByCollege: {
        url: '/api/admin/filter-by-college/:id',
        method: 'GET',
        requiresAuth: true
      },
      filterByRole: {
        url: '/api/admin/filter-by-role/:role',
        method: 'GET',
        requiresAuth: true
      }
    }
  } as const;
  
  export type ApiDictionary = typeof apiDictionary;