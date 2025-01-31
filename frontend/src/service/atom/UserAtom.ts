import { atom } from "jotai";

export const userAtom = atom({
    key: 'userAtom',
    default: {
        name: '',
        email: '',
        contact_no: '',
        college_id: '',
        department: '',
    },
});