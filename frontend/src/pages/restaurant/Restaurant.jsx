import axios from "axios"
import { Col, Row } from 'react-bootstrap';
import { Search } from './Search';
import { List } from './List';
import { useEffect, useState, useRef } from 'react';
import './restaurant.css'

export function Restaurant() {
    const [search, setSearch] = useState('')
    const [isSearching, setIsSearching] = useState(true);
    const [restaurants, setRestaurants] = useState([])
    const [message, setMessage] = useState('Loading...')
    const inputSearch = useRef('')

    const handleSubmit = (event) => {
        event.preventDefault()
        setSearch(inputSearch.current.value)
        setMessage('Loading...')
        setIsSearching(true)
    }

    useEffect(() => {
        const fetchRestaurants = () => {
            axios.get(`/api/restaurants?search=${search}`)
                .then((response) => {
                    setRestaurants(response.data.data)
                    setMessage('')
                    setIsSearching(false)
                }).catch((error) => {
                    setMessage(error.message)
                    setRestaurants([])
                    setIsSearching(false)
                })
        }

        fetchRestaurants()
    }, [search])

    return (
        <>
            <div className="mb-3" >
                <h1 className="fw-bold" >ร้านอาหาร</h1>
            </div>
            <Row className='justify-content-end' >
                <Col lg={4} >
                    <Search isSearching={isSearching} inputSearch={inputSearch} handleSubmit={handleSubmit} />
                </Col>
            </Row>
            <Row>
                <Col>
                    {
                        message
                            ? (<h3 className="fw-bold" >{message}</h3>)
                            : (<List restaurants={restaurants} />)
                    }
                </Col>
            </Row>
        </>
    )
}