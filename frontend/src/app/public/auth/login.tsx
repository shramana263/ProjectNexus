import React, { useState } from 'react'
import { login, sendOtp } from '../../../api/login/Api'

const Login = () => {
  const [email, setEmail] = useState<string>('')
  const [password, setPassword] = useState<string>('')
  const [loading, setLoading] = useState<boolean>(false)
  const onClick= async()=>{
    setLoading(true)
    const data = {
      email: email,
      password: password
    }
    const responseData = await login(data)
    console.log(responseData);
    
    if(responseData.status===200){
      localStorage.setItem('token',responseData.data.token)
      
      console.log('login success')  
      alert('login success')
    }else{
      console.log('login failed')  
      alert('login failed')
    }
    setLoading(false)
  }
  return (
    <div>
      <h1>Login</h1>
      <p>Enter your email and password</p>
      <input type="text"  value={email} onChange={(e)=>{setEmail((e.target as HTMLInputElement).value)}}/>
      <input type="text"  value={password} onChange={(e)=>{setPassword((e.target as HTMLInputElement).value)}}/>
      <button onClick={onClick} disabled={loading?true:false}>Login</button>
    </div>
  )
}

export default Login