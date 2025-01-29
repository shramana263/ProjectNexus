import { backendDomain } from '../constant';

interface AuthRequestBodies {

  sendOtp: {
    // email: string;
    name: string;
    email: string;
    role: 'principal' | 'faculty';
    contact_no: string;
    password: string;
    college_id?: string;
    departmant?: string;
  };
  register: {
    email:string
  };

  login: {
    email: string;
    password: string;
  };
  updateData: {
    name: string;
    email: string;
    contact_no: string;
    college_id?: string;
    department?: string;
  };
  resetPassword: {
    email: string;
    // password: string;
    otp: string;
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
    college_id?: string;

  };
  updateUser: {
    uuid: string;
    name: string;
    email: string;
    role: 'admin' | 'principal' | 'faculty';
    contact_no: string;
    college_id?: string;
    department?: string;
  };
  updateCollege: {
    name: string;
    address: string;
    contact_no?: string;
  };
}

// API Dictionary
export const apiDictionary = {
  auth: {
    //1.When the user first try to register with user data, this call will be done to send otp to the email of the user to verify it.
    sendOtp: {
      url: '/api/auth/send-otp',
      method: 'POST',
      body: {} as AuthRequestBodies['sendOtp'],
      requiresAuth: false
    },
    //2.after getting email, this call will be done to actually register the user with the valid email and other data.
    register: {
      url: '/api/auth/register',
      method: 'POST',
      body: {} as AuthRequestBodies['register'],
      requiresAuth: false
    },
    //for login of the alrady existing user
    login: {
      url: '/api/auth/login',
      method: 'POST',
      body: {} as AuthRequestBodies['login'],
      requiresAuth: false
    },
    //logout of the authenticated logged in user
    logout: {
      url: '/api/auth/logout',
      method: 'POST',
      requiresAuth: true
    },
    //get the user data of the authenticated user
    getUser: {
      url: '/api/auth/user',
      method: 'GET',
      requiresAuth: true
    },

    //1.forget password at the time of login, send otp to the email of the user
    forgetPassword: {
      url: '/api/auth/forget-password',
      method: 'POST',
      body: { email: '' },
      requiresAuth: false
    },
    //2.valid login of the user after getting otp
    resetPassword: {
      url: '/api/auth/reset-password',
      method: 'POST',
      body: {} as AuthRequestBodies['resetPassword'],
      requiresAuth: false
    },

    //update the user data of the authenticated user
    updateData: {
      url: '/api/auth/update-data',
      method: 'POST',
      body: {} as AuthRequestBodies['updateData'],
      requiresAuth: true
    }
  },
  admin: {
    //add new college by the admin
    addCollege: {
      url: '/api/admin/add-college',
      method: 'POST',
      body: {} as AdminRequestBodies['addCollege'],
      requiresAuth: true
    },
    //add new user by the admin
    addUser: {
      url: '/api/admin/add-user',
      method: 'POST',
      body: {} as AdminRequestBodies['addUser'],
      requiresAuth: true
    },
    //update the user data by the admin
    updateUser: {
      url: '/api/admin/update-user',
      method: 'POST',
      body: {} as AdminRequestBodies['updateUser'],
      requiresAuth: true
    },
    //update the college data by the admin
    updateCollege: {
      url: '/api/admin/update/college/:id',
      method: 'POST',
      body: {} as AdminRequestBodies['updateCollege'],
      requiresAuth: true
    },
    //delete any college data by the admin
    deleteCollege: {
      url: '/api/admin/delete/college/:id',
      method: 'DELETE',
      requiresAuth: true
    },
    //get faculty data filtered by college
    filterByCollege: {
      url: '/api/admin/filter-by-college/:id',
      method: 'GET',
      requiresAuth: true
    },
    //get faculty data filtered by role
    filterByRole: {
      url: '/api/admin/filter-by-role/:role',
      method: 'GET',
      requiresAuth: true
    }
  }
} as const;

export type ApiDictionary = typeof apiDictionary;