import { CardHeader, Col, Row } from "react-bootstrap"
import { Card, CardBody } from "react-bootstrap"

export function List({ restaurants }) {
    return (
        <>
            <h4>ผลการค้นหา {restaurants.length} รายการ</h4>

            <Row>

                {restaurants.length > 0 && restaurants.map((restaurant) => {
                    let name = restaurant.name || restaurant.name_th || restaurant.name_en || 'ไม่ได้ระบุ'
                    return (
                        <Col key={restaurant.osm_id} lg={3} md={4} className="my-1"  >
                            <Card>
                                <CardHeader>
                                    <div className="fs-6 fw-bold" >
                                        {name}
                                    </div>
                                </CardHeader>
                                <CardBody>
                                    <div className="detail-container" >
                                        <div className="detail-title" >
                                            Cuisine:
                                        </div>
                                        <div className="" >
                                            {restaurant.cuisine ? restaurant.cuisine : 'ไม่ระบุ'}
                                        </div>
                                    </div>
                                    <hr />
                                    <div className="detail-container" >
                                        <div className="detail-title" >
                                            ละติจูด:
                                        </div>
                                        <div className="" >
                                            {restaurant.lat}
                                        </div>
                                    </div>
                                    <hr />
                                    <div className="detail-container" >
                                        <div className="detail-title" >
                                            ลองติจูด:
                                        </div>
                                        <div className="" >
                                            {restaurant.lon}
                                        </div>
                                    </div>
                                    <hr />
                                    <div>
                                        <a target="_blank" href={`https://www.openstreetmap.org/node/${restaurant.osm_id}`}>
                                            ดูในแผนที่
                                        </a>
                                    </div>
                                </CardBody>
                            </Card>
                        </Col>
                    )

                })}
            </Row>
        </>
    )
}