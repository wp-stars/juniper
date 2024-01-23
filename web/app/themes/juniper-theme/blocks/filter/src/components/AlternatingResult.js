import React, { useState, useRef, useEffect } from "react"
import Lottie from "lottie-react";
import animation from "../lottie/arrowanimation.json"

const AlternatingResult = ({ index, post }) => {

    const lottieRef = useRef();
  
    useEffect(() => {
        lottieRef.current.goToAndStop(0);
      }, []); 
    
      const handleMouseEnter = () => {
        lottieRef.current.setDirection(1);
        lottieRef.current.play();
      };
    
      const handleMouseLeave = () => {
        lottieRef.current.setDirection(-1);
        lottieRef.current.play();
      };
      
    return (
      
        <div className={`alternating-results container mx-auto min-h-[600px] mb-52 grid grid-cols-1 sm:grid-cols-2 ${index % 2 === 0 ? 'even' : 'odd'}`}>
            <div className={`order-1 ${index % 2 === 0 ? 'sm:order-1' : 'sm:order-2'}`}>
                <div className="min-h-[650px] sm:min-h-[unset]">
                    <div className="teaser-image absolute z-0">
                        <div className="absolute decoration"></div>
                        <img className="absolute" src={post.fields.teaser_image} />
                    </div>
                    <div className="showcase-image z-10 relative">
                        <img className="max-w-[300px]" alt="Showcase Image" src={post.fields.showcase_image} />
                    </div>
                </div>
            </div>
            <div className={`order-2 ${index % 2 === 0 ? 'sm:order-2' : 'sm:order-1'} flex flex-col justify-center text-left`}>
                <h3>{post.post_title} // {post.fields.year}</h3>
                <p className="mb-10">
                    {post.terms.map((term, index) => (
                            <React.Fragment key={index}>
                                {index < post.terms.length && index > 0 && ' // '}
                                <span dangerouslySetInnerHTML={{ __html: `${term.name}` }}></span>
                            </React.Fragment>
                        )
                    )}
                </p>
                <div className="mb-20">
                    {post.excerpt}
                </div>
                <a href={post.link} onMouseEnter={handleMouseEnter} onMouseLeave={handleMouseLeave} className="btn-animation">
                        <div className="flex flex-row items-center">
                            <span className="btn-underline"> Mehr über {post.post_title}</span>
                            <Lottie
                                lottieRef={lottieRef}
                                animationData={animation}
                                style={{ height: 50, width: 50 }}
                                loop={false}
                                className="lottie-animation"
                            />
                        </div>
                    </a>
            </div>
        </div>
    )
}

export default AlternatingResult
