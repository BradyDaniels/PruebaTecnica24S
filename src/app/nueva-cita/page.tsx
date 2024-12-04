'use client'

import React,{useEffect, useState} from 'react'

function Doctor({index,doc}){
    
    const [details,setDetails]=useState(false);
    const [servicios,setServicios]=useState([]);
    const [fechaCita,setFechaCita]=useState('');
    const [horaCita,setHoraCita]=useState('');
    const [titulo,setTitulo]=useState('');
    const [error,setError]=useState('');
    const [loading,setLoading]=useState(true);

    const addCita= async ()=>{
        try {
          const response = await fetch('http://localhost/24siete_prueba/api/citas',{
            method: 'POST',
            headers:{
              'Content-Type':'aplication/json',
            },
            body:JSON.stringify({
              "DoctorID": doc.ID,
              "Titulo": titulo,
              "FechaCita": fechaCita,
              "HoraCita": horaCita,
              "PacienteID":1,
              "Estado":0
            })
          }); // Ajusta la ruta según tu API
          if (!response.ok) {
            throw new Error('Error al obtener los servicios');
          }
        } catch (err) {
          setError(err.message);
        } finally {
           window.location.href='/mi-agenda'
        }
      }
    


    const fetchServicios = async () => {
        try {
         const response = await fetch('http://localhost/24siete_prueba/api/servicios?IDDoctor='+doc.ID); // Ajusta la ruta según tu API
         if (!response.ok) {
            throw new Error('Error al obtener los servicios');
         }
          const data = await response.json();
          setServicios(data);
          setDetails(!details)
         } catch (err) {
            setError(err.message);
          } finally {
            setLoading(false);
          }
    };
    
    return <div className='w-full flex flex-col justify-center items-center'>
        <div className="w-full flex flex-row justify-between items-center">
          <img className="w-[45px] h-[45px]" src='/medic.svg'/>
          <p> {doc.NombreCompleto} </p>
          <button onClick={()=>fetchServicios()} className="w-full h-[30px] max-w-[120px] rounded-lg bg-black">
            Detalles
          </button>
        </div>
        {
          details ? 
           <div className='p-[20px] border border-gray-300 bg-opacity-20 bg-gray-500 w-full h-auto min-h-[90px] flex flex-col justify-center'>
             <div className='space-y-[10px] w-full flex flex-row flex-wrap justify-start items-center'>
                <p>Servicios:</p>
                {servicios.length>0 ? servicios.map((servicio,index)=>{
                    return <div key={index} className='rounded-lg p-[3px] ml-5 w-full max-w-[140px] flex flex-row justify-center bg-opacity-50 bg-gray-500'>
                    <p className='font-medium'>{servicio.Titulo} {'$'}{servicio.Precio}</p>
                 </div>
                }): <p>no hay servicios disponibles</p>}
             </div> 
             <div className='mt-[40px] w-full max-w-[490px] flex flex-row justify-between items-center space-x-[10px] '>
                <div className='w-full max-w-[120px] h-full flex flex-col justify-center items-center'>
                    <p>Agenda cita</p>
                    <input value={fechaCita} onChange={(event)=>setFechaCita(event.target.value)} type="date" placeholder="Fecha" className="w-full rounded-lg  text-black border-2 border-gray-500 px-[10px] my-[5px] h-[35px] border focus:outline-none focus:ring-2 focus:ring-black-600 focus:border-transparent"/>
                </div>
                <div className='w-full max-w-[120px] h-full flex flex-col justify-center items-center'>
                    <p>A la hora</p>
                    <input value={horaCita}  onChange={(event)=>setHoraCita(event.target.value)} type="time" placeholder="Hora" className="w-full rounded-lg  text-black border-2 border-gray-500 px-[10px] my-[5px] h-[35px] border focus:outline-none focus:ring-2 focus:ring-black-600 focus:border-transparent"/>
                </div>
                <div className='w-full max-w-[120px] h-full flex flex-col justify-center items-center'>
                    <p>Titulo</p>
                    <input value={titulo} onChange={(event)=>setTitulo(event.target.value)} type="text" placeholder="Titulo" className="w-full rounded-lg  text-black border-2 border-gray-500 px-[10px] my-[5px] h-[35px] border focus:outline-none focus:ring-2 focus:ring-black-600 focus:border-transparent"/>
                </div>
             </div>
             <button onClick={()=>addCita()} className="mx-auto mt-[30px] w-full h-[30px] max-w-[120px] rounded-lg bg-black">
                Agendar
             </button>
           </div>
          :<></> 
        }
    </div> 
}



export default function NuevaCita(){

    const [ubicaciones,setUbicaciones]=useState([]);
    const [especialidades,setEspecialidades]=useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    
    useEffect(() => {
        const fetchUbicaciones = async () => {
          try {
            const response = await fetch('http://localhost/24siete_prueba/api/ubicaciones'); // Ajusta la ruta según tu API
            if (!response.ok) {
              throw new Error('Error al obtener los servicios');
            }
            const data = await response.json();

            setUbicaciones(data);
          } catch (err) {
            setError(err.message);
          } finally {
            setLoading(false);
          }
        };
    
        const fetchEspecialidades = async () => {
          try {
            const response = await fetch('http://localhost/24siete_prueba/api/especialidades'); // Ajusta la ruta según tu API
            if (!response.ok) {
              throw new Error('Error al obtener los servicios');
            }
            const data = await response.json();
    
            setEspecialidades(data);
          } catch (err) {
            setError(err.message);
          } finally {
            setLoading(false);
          }
        };
        fetchUbicaciones();
        fetchEspecialidades();
    }, []); // El array vacío asegura que se ejecute solo una vez al montar el componente
    
    const [ubicacionID, setUbicacionID] = useState('');
    const [especialidadID, setEspecialidadID] = useState('');
    const [doctores, setDoctores] = useState([]);

    const handleUbicacionChange = (e) => {
        setUbicacionID(e.target.value);
    };

    const handleEspecialidadChange = (e) => {
        setEspecialidadID(e.target.value);
    };

    useEffect(() => {
        const fetchData = async () => {
            console.log(ubicacionID);
            console.log(especialidadID);
            if (ubicacionID && especialidadID) {
                try {
                    const response = await fetch(`http://localhost/24siete_prueba/api/doctores?IDUbicacion=${ubicacionID}&IDEspecialidad=${especialidadID}`);
                    if (!response.ok) {
                        throw new Error('Error al obtener las citas');
                    }
                    const data = await response.json();
                    setDoctores(data); // Guarda las citas en el estado
                } catch (error) {
                    console.error(error);
                }
            }
        };

        fetchData();
    }, [ubicacionID, especialidadID]); // Se ejecuta cuando cambian los IDs

    


    return <div className="p-[40px] w-full h-full flex flex-col justify-center items-center">
     <a href='/mi-agenda'>
       Mi agenda
     </a>   
     <div className="px-[30px] py-[25px] rounded-2xl w-full h-full min-h-[650px] mx-auto flex flex-col items-center justify-start bg-white border border-gray-200 max-w-[720px]">
       <h2 className="w-full max-w-[450px] text-center mb-[30px]">Agenda una nueva cita con alguno de nuestros medicos!</h2>
       <div className="space-y-[25px] px-[50px] mt-[45px] w-full h-full flex flex-col justify-start items-center">
          <div className="w-full space-x-[15px] flex flex-row justify-start items-center">
              <p>Busco medico en: </p>
              <select
                id="ubicacion"
                className="mr-[30px] text-black border border-gray-300 rounded-md p-2 w-full max-w-[200px]"
                value={ubicacionID}
                onChange={handleUbicacionChange}
              >
                <option disabled defaultChecked value={"todos"}>Seleciona</option>
                {loading && !error ?  <option value=" ">Cargando...</option> :
                  ubicaciones.map((ubi,index)=>{
                    return <option key={index} value={ubi.ID}>{ubi.Titulo}</option>
                  })
                }
              </select>
          </div>
          <div className="w-full space-x-[15px] flex flex-row justify-start items-center">
              <p>Especializado en: </p>
              <select
                id="especialidad"
                className="mr-[30px] text-black border border-gray-300 rounded-md p-2 w-full max-w-[200px]"
                value={especialidadID}
                onChange={handleEspecialidadChange} 
              >
                <option disabled defaultChecked value={"todos"}>Selecciona</option>
                {loading && !error ?  <option value=" ">Cargando...</option> :
                  especialidades.map((esp,index)=>{
                    return <option key={index} value={esp.ID}>{esp.Titulo}</option>
                  })
                }
              </select>
          </div>
          <div className="space-y-[15px] w-full border-t border-gray-300 h-full py-[25px]">
             {/*<p className="w-full text-center">Selecciona una ubicacion y una especialidad</p>*/}
             {doctores.length>0 ? doctores.map((doctor,index)=>{
                return <Doctor key={index} doc={doctor} index={doctor.ID}/>
             }): <p className="text-black w-full text-center">Selecciona otra ubicacion y/o especialidad</p>}

          </div>
       </div>
     </div>
    </div>
}